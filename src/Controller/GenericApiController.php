<?php

namespace App\Controller;

use App\Repository\ApiLookupConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1', name: 'api_generic_')]
class GenericApiController extends AbstractController
{
    private const ENTITY_MAP = [
        'page-types'      => \App\Entity\ManagementPagetypes::class,
        'episodes'        => \App\Entity\ApiEpisodes::class,
        'actors'          => \App\Entity\ApiActors::class,
        'shows'           => \App\Entity\ApiShows::class,
        'seasons'         => \App\Entity\ApiSeasons::class,
        'managementusers' => \App\Entity\ManagementUsers::class,
        // Voeg hier de rest van je 70 entiteiten toe
    ];

    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/{entitySlug}', name: 'list', methods: ['GET'])]
    public function list(string $entitySlug, Request $request): JsonResponse
    {
        $blockedEntities = ['managementusers', 'users'];

        if (in_array(strtolower($entitySlug), $blockedEntities, true)) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        }

        $entityClass = $this->resolveEntityClass($entitySlug);

        if (!$entityClass) {
            return new JsonResponse(['error' => sprintf('Endpoint "%s" niet gevonden.', $entitySlug)], Response::HTTP_NOT_FOUND);
        }

        $limit = $request->query->getInt('limit', 50);
        $page = $request->query->getInt('page', 1);
        $offset = ($page - 1) * $limit;

        $repository = $this->entityManager->getRepository($entityClass);
        $entities = $repository->findBy([], null, $limit, $offset);

        $data = array_map(fn($item) => $this->normalizeEntity($item), $entities);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/{entitySlug}/{id}', name: 'show', methods: ['GET'])]
    public function show(string $entitySlug, mixed $id): JsonResponse
    {
        $entityClass = $this->resolveEntityClass($entitySlug);
        $blockedEntities = ['managementusers', 'users'];

        if (in_array(strtolower($entitySlug), $blockedEntities, true)) {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        }

        if (!$entityClass) {
            return new JsonResponse(['error' => sprintf('Endpoint "%s" niet gevonden.', $entitySlug)], Response::HTTP_NOT_FOUND);
        }

        $item = $this->entityManager->getRepository($entityClass)->find($id);

        if (!$item) {
            return new JsonResponse(['error' => 'Item niet gevonden.'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($this->normalizeEntity($item), Response::HTTP_OK);
    }

    #[Route('/lookup/{entitySlug}', name: 'lookup_list', methods: ['GET'], priority: 10)]
    public function lookupList(
        string $entitySlug,
        Request $request,
        ApiLookupConfigRepository $configRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        // 1. Haal de instellingen op uit de database
        $config = $configRepository->findBySlug($entitySlug);

        if (!$config) {
            return new JsonResponse(
                ['error' => sprintf('Geen lookup-configuratie gevonden voor "%s".', $entitySlug)],
                Response::HTTP_NOT_FOUND
            );
        }

        $entityClass = $config->getEntityClass();
        $idField = $config->getIdColumn();
        $valueField = $config->getValueColumn();
        $allowedFilters = $config->getAllowedFilters() ?? [];

        $repository = $em->getRepository($entityClass);
        $qb = $repository->createQueryBuilder('e');

        $joinedRelations = [];

        // 2. Beveiligd filteren via GET params
        foreach ($request->query->all() as $param => $val) {
            if ($val === null || $val === '') {
                continue;
            }

            if (!in_array($param, $allowedFilters, true)) {
                continue;
            }

            $rawVal = str_replace('+', ',', $val);
            $isMultiple = str_contains($rawVal, ',');
            $filterValue = $isMultiple ? array_map('trim', explode(',', $rawVal)) : $val;

            // Directe kolom op de entiteit zelf
            if (property_exists($entityClass, $param)) {
                if ($isMultiple) {
                    $qb->andWhere("e.{$param} IN (:{$param})")
                        ->setParameter($param, $filterValue);
                } else {
                    $qb->andWhere("e.{$param} = :{$param}")
                        ->setParameter($param, $filterValue);
                }
                continue;
            }

            // Relatie-filter via underscore (bijv. ?show_id=1)
            if (str_contains($param, '_')) {
                [$relation, $field] = explode('_', $param, 2);

                if (property_exists($entityClass, $relation)) {
                    if (!isset($joinedRelations[$relation])) {
                        $qb->leftJoin("e.{$relation}", $relation);
                        $joinedRelations[$relation] = true;
                    }

                    if ($isMultiple) {
                        $qb->andWhere("{$relation}.{$field} IN (:{$param})")
                            ->setParameter($param, $filterValue);
                    } else {
                        $qb->andWhere("{$relation}.{$field} = :{$param}")
                            ->setParameter($param, $filterValue);
                    }
                }
            }
        }

        // 3. Haal puur de ID en Value op voor generieke lookups (geen custom code-if-statements meer!)
        $qb->select("e.{$idField} AS id, e.{$valueField} AS text");

        $results = $qb->getQuery()->getArrayResult();

        return new JsonResponse($results, Response::HTTP_OK);
    }

    private function normalizeEntity(object $entity): array
    {
        if ($entity instanceof \JsonSerializable) {
            return $entity->jsonSerialize();
        }

        $reflect = new \ReflectionClass($entity);
        $data = [];

        foreach ($reflect->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $name = $method->getName();

            if ((str_starts_with($name, 'get') || str_starts_with($name, 'is')) && $method->getNumberOfParameters() === 0) {
                $prefixLength = str_starts_with($name, 'get') ? 3 : 2;
                $key = lcfirst(substr($name, $prefixLength));

                try {
                    $value = $method->invoke($entity);

                    if ($value instanceof \DateTimeInterface) {
                        $value = $value->format('Y-m-d H:i:s');
                    } elseif (is_object($value)) {
                        if (method_exists($value, 'getId')) {
                            $value = $value->getId();
                        } elseif (method_exists($value, 'getUserId')) {
                            $value = $value->getUserId();
                        } else {
                            $value = (string) $value;
                        }
                    }

                    $data[$key] = $value;
                } catch (\Throwable $e) {
                    continue;
                }
            }
        }

        return $data;
    }

    private function resolveEntityClass(string $slug): ?string
    {
        return self::ENTITY_MAP[strtolower($slug)] ?? null;
    }
}
