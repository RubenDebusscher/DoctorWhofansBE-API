<?php

namespace App\Controller\Api;

use App\Entity\V3Attributes;
use App\Entity\V3Contenttypes;
use App\Entity\V3Itemattributes;
use App\Entity\V3Items;
use App\Repository\V3ItemsRepository;
use App\Service\AttributeValueManager;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(V3ItemsRepository $repository): JsonResponse
    {
        return $this->json(
            $repository->findAll(),
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

        // 1. DATA UITLEZEN (Ondersteunt JSON én FormData voor Image upload)
        if (str_contains($contentType, 'multipart/form-data')) {
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

            // Verwerk de fysieke afbeelding upload als deze in de FormData zit
            $file = $request->files->get('image') ?? $request->files->get('file');
            if ($file) {
                // 1. Bouw het absolute pad op naar de map van het specifieke item
                $targetDir = $cdnMountPath .'items/'. $item->getItemid();

                // 2. Controleer of de map al bestaat; zo niet, maak deze aan
                if (!is_dir($targetDir)) {
                    // 0775 geeft lees- en schrijfrechten aan de eigenaar en de groep
                    mkdir($targetDir, 0775, true);
                }

                // 3. Vaste bestandsnaam instellen
                $filename = 'cover.jpg';

                // 4. Verplaats het bestand (overschrijft automatisch als cover.jpg al bestaat)
                $file->move($targetDir, $filename);

                // 5. Sla het relatieve pad op in het Item-object voor de database
                $item->setImage('items/' . $item->getItemid() . '/' . $filename);
            }
        } else {
            // Pure JSON
            $data = json_decode($request->getContent(), true) ?? [];

            // Denormaliseer basisvelden (inclusief 'image' als pad/string meegegeven wordt!)
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

            // Koppel ContentType
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
        $userId = $user && method_exists($user, 'getId') ? $user->getId() : 1;

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

        // Herlaad voor zuivere response
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
