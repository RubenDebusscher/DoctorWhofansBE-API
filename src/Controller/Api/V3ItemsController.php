<?php

namespace App\Controller\Api;

use App\Entity\Code;
use App\Entity\V3Items;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v3/items')]
class V3ItemsController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): JsonResponse
    {


        // 1. Query parameters ophalen
        $search = $request->query->get('q'); // <-- NIEUW: zoekterm ophalen
        $typeName = $request->query->get('type_name');
        $typeId = $request->query->get('type');
        // Veilige manier om page en limit op te halen
        $pageRaw = $request->query->get('page');
        $limitRaw = $request->query->get('limit');

        $page = is_numeric($pageRaw) ? max(1, (int) $pageRaw) : 1;
        $limit = is_numeric($limitRaw) ? min(100, max(1, (int) $limitRaw)) : 20;

        $offset = ($page - 1) * $limit;
        $offset = ($page - 1) * $limit;

        // 2. QueryBuilder opbouwen
        $qb = $em->createQueryBuilder()
            ->select('i', 't', 'ia')
            ->from(V3Items::class, 'i')
            ->leftJoin('i.type', 't')
            ->leftJoin('i.itemAttributes', 'ia');

        // NIEUW: Zoeken op de naam van het item (Case-Insensitive)
        if ($search !== null && trim($search) !== '') {
            $qb->andWhere('LOWER(i.name) LIKE :query')
                ->setParameter('query', '%' . strtolower(trim($search)) . '%');
        }

        // 3. Filteren op type_name of typeId
        if ($typeName !== null && $typeName !== '') {
            $types = array_map('trim', explode(',', $typeName));
            $qb->andWhere('LOWER(t.codeValue) IN (:types) OR LOWER(t.label) IN (:types)')
                ->setParameter('types', array_map('strtolower', $types));
        }

        if ($typeId !== null && $typeId !== '') {
            $qb->andWhere('t.id = :typeId')
                ->setParameter('typeId', (int) $typeId);
        }

        // 4. Paginering instellen
        $qb->setFirstResult($offset)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb->getQuery(), true);
        $totalItems = count($paginator);

        $items = iterator_to_array($paginator);

        return $this->json(
            [
            'data' => $items,
            'meta' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $totalItems,
                'pages' => (int) ceil($totalItems / $limit),
            ]
            ],
            Response::HTTP_OK,
            [],
            [
            'groups' => ['v3_item:list'],
            'circular_reference_handler' => function ($object) {
                return method_exists($object, 'getId') ? $object->getId() : null;
            }
            ]
        );
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(?V3Items $entity): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $entity,
            Response::HTTP_OK,
            [],
            [
                'groups' => ['v3_item:list', 'v3_item:detail'],
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                'circular_reference_handler' => function ($object) {
                    if (method_exists($object, 'getItemid')) {
                        return $object->getItemid();
                    }
                    return method_exists($object, 'getId') ? $object->getId() : null;
                }
            ]
        );
    }

    #[Route('', methods: ['POST'])]
    #[Route('/{id}', methods: ['POST', 'PUT'])]
    public function save(
        ?V3Items $item,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $cdnMountPath = $this->getParameter('cdn_mount_path');
        $item ??= new V3Items();
        $contentType = $request->headers->get('Content-Type', '');

        // 1. DATA UITLEZEN
        if (str_contains($contentType, 'multipart/form-data')) {
            if ($request->request->has('itemName')) {
                $item->setName($request->request->get('itemName'));
            } elseif ($request->request->has('name')) {
                $item->setName($request->request->get('name'));
            }

            if ($request->request->has('type') || $request->request->has('contenttypeid')) {
                $typeId = (int) ($request->request->get('type') ?? $request->request->get('contenttypeid'));
                $typeEntity = $em->getRepository(Code::class)->find($typeId);
                if ($typeEntity) {
                    $item->setType($typeEntity);
                }
            }

            $uploadedFile = $request->files->get('image') ?? $request->files->get('file');
            if ($uploadedFile) {
                $typeName = $item->getType()
                    ? ($item->getType()->getCodeValue() ?? $item->getType()->getLabel())
                    : 'General';
                $itemId = $item->getItemid();

                $targetDir = rtrim($cdnMountPath, '/') . '/items/' . $typeName . '/' . $itemId;

                if (!is_dir($targetDir)) {
                    if (!mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
                        throw new \RuntimeException(sprintf('Directory "%s" kan niet worden aangemaakt', $targetDir));
                    }
                }

                $filename = 'cover.jpg';
                $targetPath = $targetDir . '/' . $filename;
                $sourcePath = $uploadedFile->getPathname();

                if (copy($sourcePath, $targetPath)) {
                    @unlink($sourcePath);
                    $item->setImage('items/' . $typeName . '/' . $itemId . '/' . $filename);
                } else {
                    throw new \RuntimeException(sprintf('Kan bestand niet kopiëren van %s naar %s', $sourcePath, $targetPath));
                }
            }
        } else {
            $data = json_decode($request->getContent(), true) ?? [];

            $serializer->denormalize(
                $data,
                V3Items::class,
                'json',
                [
                    'object_to_populate' => $item,
                    'groups' => ['v3_item:write'],
                    AbstractNormalizer::IGNORED_ATTRIBUTES => ['itemAttributes', 'type']
                ]
            );

            $typeId = null;
            if (isset($data['type'])) {
                $typeId = is_array($data['type'])
                    ? ($data['type']['id'] ?? $data['type']['codeid'] ?? null)
                    : $data['type'];
            } elseif (isset($data['contenttypeid'])) {
                $typeId = $data['contenttypeid'];
            }

            if ($typeId) {
                $typeEntity = $em->getRepository(Code::class)->find((int)$typeId);
                if ($typeEntity) {
                    $item->setType($typeEntity);
                }
            }
        }

        // 2. GEBRUIKERSCONTEXT
        $user = $this->getUser();
        if (!$item->getItemid() && $user) {
            $item->setCreatedBy($user);
        }
        if ($user) {
            $item->setUpdatedBy($user);
        }

        if ($item->getImage() === null) {
            $item->setImage('');
        }

        // 3. OPSLAAN
        $em->persist($item);
        $em->flush();
        $em->refresh($item);

        return $this->json(
            $item,
            Response::HTTP_OK,
            [],
            [
                'groups' => ['v3_item:list', 'v3_item:detail'],
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                'circular_reference_handler' => function ($object) {
                    if (method_exists($object, 'getItemid')) {
                        return $object->getItemid();
                    }
                    return method_exists($object, 'getId') ? $object->getId() : null;
                }
            ]
        );
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(?V3Items $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($entity);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}/history', methods: ['GET'])]
    public function history(int $id, EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(\App\Entity\LogEntry::class);
        $item = $em->getRepository(V3Items::class)->find($id);

        if (!$item) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $logs = $repo->getLogEntries($item);

        return $this->json($logs, Response::HTTP_OK);
    }
}
