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
        EntityManagerInterface $em,
        AttributeValueManager $attrValueManager
    ): JsonResponse {

        $item ??= new V3Items();
        $contentType = $request->headers->get('Content-Type', '');
        $rawAttributes = [];

        // SITUATIE A: FormData (Formulier)
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

            if ($request->request->has('itemAttributes')) {
                $attrInput = $request->request->get('itemAttributes');
                $rawAttributes = is_string($attrInput) ? (json_decode($attrInput, true) ?? []) : $attrInput;
            }

        } else {
            // SITUATIE B: Pure JSON
            $data = json_decode($request->getContent(), true) ?? [];

            // 1. Isoleer attributes VOORDAT we ze denormaliseren
            $rawAttributes = $data['itemAttributes'] ?? [];
            unset($data['itemAttributes']);

            // 2. Denormalizeer basisvelden
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

            // 3. Koppel de ContentType entiteit
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

        // Gebruikerscontext
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

        // Sla basisitem op zodat een nieuw item direct een ID krijgt
        $em->persist($item);
        $em->flush();

        // --- STAP 2: ATTRIBUTEN VERWERKEN & SYNCHRONISEREN ---
        if (!empty($rawAttributes) && is_array($rawAttributes)) {
            $attrRepository = $em->getRepository(V3Attributes::class);
            $now = new \DateTime();

            // Haal de in-memory verzameling op van het item
            $existingCollection = $item->getItemattributes();

            foreach ($rawAttributes as $attrData) {
                $attrId = $attrData['attributeid'] ?? ($attrData['attribute']['attributeid'] ?? null);

                if (!$attrId) {
                    continue;
                }

                $attrIdInt = (int)$attrId;

                $attributeEntity = $attrRepository->find($attrIdInt);
                if (!$attributeEntity) {
                    continue;
                }

                // 1. VOORKOM DUBBELING: Check of dit attribuut al op het item bestaat
                $itemAttrValue = null;
                if ($existingCollection) {
                    foreach ($existingCollection as $existing) {
                        $linkedAttr = $existing->getAttributeid();
                        if ($linkedAttr && $linkedAttr->getAttributeid() === $attrIdInt) {
                            $itemAttrValue = $existing;
                            break;
                        }
                    }
                }

                // 2. Maak een nieuw koppel-record aan als het nog niet bestaat
                if (!$itemAttrValue) {
                    $itemAttrValue = new V3Itemattributes();
                    $itemAttrValue->setItem($item);
                    $itemAttrValue->setAttributeid($attributeEntity);

                    $itemAttrValue->setCreatedBy($userId);
                    $itemAttrValue->setCreatedAt($now);

                    // 3. SYNCHRONISEER MET IN-MEMORY COLLECTION voor direct kloppende JSON response
                    if ($existingCollection) {
                        $existingCollection->add($itemAttrValue);
                    }
                }

                $itemAttrValue->setUpdatedBy($userId);
                $itemAttrValue->setUpdatedAt($now);

                // Geef de hele array mee aan de manager i.p.v. alleen een $val
                $attrValueManager->assignValue($itemAttrValue, $attrData, $attributeEntity);

                $em->persist($itemAttrValue);
            }

            $em->flush();
        }

        // Dwing Doctrine om alle relaties in-memory volledig te herladen
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
