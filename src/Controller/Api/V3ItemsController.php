<?php

namespace App\Controller\Api;

use App\Entity\V3Attributes;
use App\Entity\V3Contenttypes;
use App\Entity\V3Itemattributes;
use App\Entity\V3Items;
use App\Repository\V3ItemsRepository;
use App\Service\AttributeValueManager;
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
        // 1. Query parameters ophalen met defaults
        $typeName = $request->query->get('type_name'); // e.g. "Serial" of "Serial,Episode"
        $page = max(1, $request->query->getInt('page', 1));
        $limit = min(100, max(1, $request->query->getInt('limit', 20))); // Max 100 items per pagina
        $offset = ($page - 1) * $limit;

        // 2. QueryBuilder opbouwen met JOIN naar type (voorkomt N+1 overhead)
        $qb = $em->createQueryBuilder()
            ->select('i', 't')
            ->from(V3Items::class, 'i')
            ->leftJoin('i.type', 't');

        // 3. Optioneel filteren op type_name
        if ($typeName !== null && $typeName !== '') {
            $types = array_map('trim', explode(',', $typeName));
            $qb->andWhere('LOWER(t.name) IN (:types)')
                ->setParameter('types', array_map('strtolower', $types));
        }

        // 4. Paginering instellen
        $qb->setFirstResult($offset)
            ->setMaxResults($limit);

        // 5. Resultaten en totaal aantal ophalen via Paginator
        $paginator = new Paginator($qb->getQuery());
        $totalItems = count($paginator);

        return $this->json(
            [
                'data' => iterator_to_array($paginator),
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

    // CREATE (POST) & UPDATE (POST / PUT)
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
            // OPTIE A: Afbeelding Upload voor een BESTAAND item
            if ($request->request->has('itemName')) {
                $item->setName($request->request->get('itemName'));
            } elseif ($request->request->has('name')) {
                $item->setName($request->request->get('name'));
            }

            if ($request->request->has('type')) {
                $typeId = (int) $request->request->get('type');
                $typeEntity = $em->getRepository(V3Contenttypes::class)->find($typeId);
                if ($typeEntity) {
                    $item->setType($typeEntity);
                }
            }

            // Verwerk de fysieke afbeelding upload
            $uploadedFile = $request->files->get('image') ?? $request->files->get('file');
            if ($uploadedFile) {
                $typeName = $item->getType() ? $item->getType()->getName() : 'General';
                $itemId = $item->getItemid();

                // Bouw het pad op naar de specifieke map op het CDN
                $targetDir = rtrim($cdnMountPath, '/') . '/items/' . $typeName . '/' . $itemId;

                // Maak de map aan als deze nog niet bestaat
                if (!is_dir($targetDir)) {
                    if (!mkdir($targetDir, 0777, true) && !is_dir($targetDir)) {
                        throw new \RuntimeException(sprintf('Directory "%s" kan niet worden aangemaakt', $targetDir));
                    }
                }

                $filename = 'cover.jpg';
                $targetPath = $targetDir . '/' . $filename;
                $sourcePath = $uploadedFile->getPathname();

                // Kopiëren van temp-map naar de CDN mount
                if (copy($sourcePath, $targetPath)) {
                    @unlink($sourcePath); // Verwijder temp-bestand
                    $item->setImage('items/' . $typeName . '/' . $itemId . '/' . $filename);
                } else {
                    throw new \RuntimeException(sprintf('Kan bestand niet kopiëren van %s naar %s', $sourcePath, $targetPath));
                }
            }
        } else {
            // OPTIE B: Gewone JSON POST/PUT (bv. bij initial creatie van het item)
            $data = json_decode($request->getContent(), true) ?? [];

            $serializer->denormalize(
                $data,
                V3Items::class,
                'json',
                [
                    'object_to_populate' => $item,
                    'groups' => ['v3_item:write'],
                    AbstractNormalizer::IGNORED_ATTRIBUTES => ['itemAttributes']
                ]
            );

            if (isset($data['type'])) {
                $typeId = is_array($data['type']) ? ($data['type']['contenttypeid'] ?? null) : $data['type'];
                if ($typeId) {
                    $typeEntity = $em->getRepository(V3Contenttypes::class)->find((int)$typeId);
                    if ($typeEntity) {
                        $item->setType($typeEntity);
                    }
                }
            }
        }

        // 2. GEBRUIKERSCONTEXT & AUDIT LOGGING
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

        // 3. EENMALIG OPSLAAN IN DATABASE
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
                    if (method_exists($object, 'getContenttypeid')) {
                        return $object->getContenttypeid();
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
