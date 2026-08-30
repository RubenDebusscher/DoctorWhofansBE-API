<?php

namespace App\Controller\Api;

use App\Entity\CodeGroup;
use App\Repository\CodeGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/lookup/code-groups', name: 'api_v1_code_groups_', priority: 20)]
class CodeGroupController extends AbstractController
{
    /**
     * GET: Haal alle codegroepen op (voor de Admin UI)
     */
    #[Route('', name: 'list', methods: ['GET'], priority: 20)]
    public function list(CodeGroupRepository $repository): JsonResponse
    {
        $groups = $repository->findBy([], ['name' => 'ASC']);

        $data = array_map(
            fn(CodeGroup $group) => [
            'id'            => $group->getId(),
            'codeKey'       => $group->getCodeKey(),
            'name'          => $group->getName(),
            'description'   => $group->getDescription(),
            'documentation' => $group->getDocumentation(),
            'codesCount'    => $group->getCodes()->count(),
            ], $groups
        );

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * GET /{id}: Haal 1 specifieke codegroep op
     */
    #[Route('/{id}', name: 'show', methods: ['GET'], priority: 20)]
    public function show(CodeGroup $group): JsonResponse
    {
        return new JsonResponse(
            [
            'id'            => $group->getId(),
            'codeKey'       => $group->getCodeKey(),
            'name'          => $group->getName(),
            'description'   => $group->getDescription(),
            'documentation' => $group->getDocumentation(),
            'codesCount'    => $group->getCodes()->count(),
            ], Response::HTTP_OK
        );
    }

    /**
     * POST: Maak een nieuwe CodeGroup aan
     */
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];

        if (empty($data['codeKey']) || empty($data['name'])) {
            return new JsonResponse(
                ['error' => 'Velden "codeKey" en "name" zijn verplicht.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $group = new CodeGroup();
        $group->setCodeKey(trim($data['codeKey']));
        $group->setName(trim($data['name']));

        if (array_key_exists('description', $data)) {
            $group->setDescription($data['description']);
        }

        if (array_key_exists('documentation', $data)) {
            $group->setDocumentation($data['documentation']);
        }

        $em->persist($group);
        $em->flush();

        return new JsonResponse(
            [
            'id'            => $group->getId(),
            'codeKey'       => $group->getCodeKey(),
            'name'          => $group->getName(),
            'description'   => $group->getDescription(),
            'documentation' => $group->getDocumentation(),
            'message'       => 'Codegroep succesvol aangemaakt.'
            ], Response::HTTP_CREATED
        );
    }

    /**
     * PUT/PATCH: Bewerk een bestaande CodeGroup
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(
        CodeGroup $group,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];

        if (isset($data['codeKey'])) {
            $group->setCodeKey(trim($data['codeKey']));
        }
        if (isset($data['name'])) {
            $group->setName(trim($data['name']));
        }
        if (array_key_exists('description', $data)) {
            $group->setDescription($data['description']);
        }
        if (array_key_exists('documentation', $data)) {
            $group->setDocumentation($data['documentation']);
        }

        $em->flush();

        return new JsonResponse(
            [
            'id'            => $group->getId(),
            'codeKey'       => $group->getCodeKey(),
            'name'          => $group->getName(),
            'description'   => $group->getDescription(),
            'documentation' => $group->getDocumentation(),
            'message'       => 'Codegroep bijgewerkt.'
            ], Response::HTTP_OK
        );
    }

    /**
     * DELETE: Verwijder een Codegroep
     */
    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(
        CodeGroup $group,
        EntityManagerInterface $em
    ): JsonResponse {
        // Veiligheidscheck: voorkom per ongeluk wissen als er nog codes aan hangen
        if ($group->getCodes()->count() > 0) {
            return new JsonResponse(
                ['error' => sprintf('Kan deze groep niet verwijderen omdat er nog %d code(s) aan gekoppeld zijn.', $group->getCodes()->count())],
                Response::HTTP_CONFLICT
            );
        }

        $em->remove($group);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
