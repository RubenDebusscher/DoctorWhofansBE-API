<?php

namespace App\Controller\Api;

use App\Entity\ApiNicknames;
use App\Repository\ApiNicknamesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/api-nicknames')]
class ApiNicknamesController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ApiNicknamesRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll());
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(?ApiNicknames $entity): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($entity);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->toArray();
        $entity = new ApiNicknames();

        // TODO: Map hier de velden van $data naar je entiteit setter methodes
        // Bijvoorbeeld: $entity->setName($data['name'] ?? null);

        $em->persist($entity);
        $em->flush();

        return $this->json($entity, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, ?ApiNicknames $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->toArray();

        // TODO: Update hier de velden van je entiteit via setters

        $em->flush();

        return $this->json($entity);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(?ApiNicknames $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($entity);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}