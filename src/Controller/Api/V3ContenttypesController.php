<?php

namespace App\Controller\Api;

use App\Entity\V3Contenttypes;
use App\Repository\V3ContenttypesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

#[Route('/api/v3/contenttypes')]
class V3ContenttypesController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(V3ContenttypesRepository $repository): JsonResponse
    {
        return $this->json(
            $repository->findAllWithRelations(), // <-- Hier de nieuwe query gebruiken!
            Response::HTTP_OK,
            [],
            ['groups' => ['contenttype:read']]
        );
    }

    #[Route('/{contenttypeid}', methods: ['GET'])]
    public function show(#[MapEntity(mapping: ['contenttypeid' => 'contenttypeid'])] ?V3Contenttypes $entity): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'ContentType niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $entity,
            Response::HTTP_OK,
            [],
            [
            AbstractNormalizer::GROUPS => ['contenttype:read'],
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                // Return een unieke identifier of string van het object om de lus te breken
                if (method_exists($object, 'getContenttypeid')) {
                    return $object->getContenttypeid();
                }
                if (method_exists($object, 'getAttributeid')) {
                    return $object->getAttributeid();
                }
                if (method_exists($object, 'getId')) {
                    return $object->getId();
                }
                return spl_object_hash($object);
            },
            ]
        );
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->toArray();
        $entity = new V3Contenttypes();

        // 1. Naam en Omschrijving
        if (isset($data['name'])) {
            $entity->setName($data['name']);
        }
        if (isset($data['description'])) {
            $entity->setDescription($data['description']);
        }

        // 2. Datum stempels instellen
        $now = new \DateTime();
        $entity->setCreatedAt($now);
        $entity->setUpdatedAt($now);

        // 3. Optioneel: Ingelogde gebruiker toewijzen als createdBy / updatedBy
        $user = $this->getUser();
        if ($user instanceof \App\Entity\ManagementUsers) {
            $entity->setCreatedBy($user);
            $entity->setUpdatedBy($user);
        }

        $em->persist($entity);
        $em->flush();

        return $this->json(
            $entity,
            Response::HTTP_CREATED,
            [],
            ['groups' => ['contenttype:read']]
        );
    }

    #[Route('/{contenttypeid}', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, ?V3Contenttypes $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'ContentType niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->toArray();

        if (array_key_exists('name', $data)) {
            $entity->setName($data['name']);
        }
        if (array_key_exists('description', $data)) {
            $entity->setDescription($data['description']);
        }

        // 2. Datum update stempel
        $entity->setUpdatedAt(new \DateTime());

        // 3. Optioneel: Ingelogde gebruiker toewijzen als updatedBy
        $user = $this->getUser();
        if ($user instanceof \App\Entity\ManagementUsers) {
            $entity->setUpdatedBy($user);
        }

        $em->flush();

        return $this->json(
            $entity,
            Response::HTTP_OK,
            [],
            ['groups' => ['contenttype:read']]
        );
    }

    #[Route('/{contenttypeid}', methods: ['DELETE'])]
    public function delete(?V3Contenttypes $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'ContentType niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($entity);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }


}
