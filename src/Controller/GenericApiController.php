<?php

namespace App\Controller;

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
        'page-types' => \App\Entity\ManagementPagetypes::class,
        'episodes'   => \App\Entity\ApiEpisodes::class,
        'actors'     => \App\Entity\ApiActors::class,
        'shows'      => \App\Entity\ApiShows::class,
        'seasons'    => \App\Entity\ApiSeasons::class,
        'managementusers'    => \App\Entity\ManagementUsers::class,
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
            // Vraag expliciet om authenticatie via Symfony security of geef 403 Forbidden
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

        // Transformeer alle entiteiten automatisch
        $data = array_map(fn($item) => $this->normalizeEntity($item), $entities);

        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/{entitySlug}/{id}', name: 'show', methods: ['GET'])]
    public function show(string $entitySlug, mixed $id): JsonResponse
    {
        $entityClass = $this->resolveEntityClass($entitySlug);
        $blockedEntities = ['managementusers', 'users'];

        if (in_array(strtolower($entitySlug), $blockedEntities, true)) {
            // Vraag expliciet om authenticatie via Symfony security of geef 403 Forbidden
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

    /**
     * Leest automatisch alle getXxx() methodes uit van het object en bouwt een simpele array.
     * Voorkomt circular references en geheugenproblemen.
     */
    private function normalizeEntity(object $entity): array
    {
        // Als de entiteit toch al JsonSerializable implementeert, gebruik die:
        if ($entity instanceof \JsonSerializable) {
            return $entity->jsonSerialize();
        }

        $reflect = new \ReflectionClass($entity);
        $data = [];

        foreach ($reflect->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $name = $method->getName();

            // Zoek alleen naar getters zonder benodigde argumenten (getters/isGetters)
            if ((str_starts_with($name, 'get') || str_starts_with($name, 'is')) && $method->getNumberOfParameters() === 0) {

                // Bepaal de key naam (bijv. getPagetypeName -> pagetypeName)
                $prefixLength = str_starts_with($name, 'get') ? 3 : 2;
                $key = lcfirst(substr($name, $prefixLength));

                try {
                    $value = $method->invoke($entity);

                    // Formatteer speciale types netjes voor JSON
                    if ($value instanceof \DateTimeInterface) {
                        $value = $value->format('Y-m-d H:i:s');
                    } elseif (is_object($value)) {
                        // Voor gekoppelde entiteiten (relaties): pak enkel het ID i.p.v. het hele object
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
                    // Negeer methodes die een fout gooien bij aanroepen
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
