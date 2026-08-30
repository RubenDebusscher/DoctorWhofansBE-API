<?php

namespace App\Controller\Api;

use App\Entity\V3Attributes;
use App\Entity\V3Validationrules;
use App\Repository\V3AttributesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v3/attributes')]
class V3AttributesController extends AbstractController
{
    private const SERIALIZATION_CONTEXT = [
        'groups' => ['v3_attributes:read']
    ];

    #[Route('', methods: ['GET'])]
    public function index(V3AttributesRepository $repository): JsonResponse
    {
        return $this->json(
            $repository->findAll(),
            Response::HTTP_OK,
            [],
            self::SERIALIZATION_CONTEXT
        );
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(?V3Attributes $entity): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $entity,
            Response::HTTP_OK,
            [],
            self::SERIALIZATION_CONTEXT
        );
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->toArray();
        $entity = new V3Attributes();

        // Verplichte/Optionele velden toewijzen via setters
        if (isset($data['name'])) {
            $entity->setName($data['name']);
        }
        if (array_key_exists('description', $data)) {
            $entity->setDescription($data['description']);
        }
        if (array_key_exists('visibility', $data)) {
            $entity->setVisibility((bool) $data['visibility']);
        }
        if (array_key_exists('lookuptable', $data)) {
            $entity->setLookuptable($data['lookuptable']);
        }
        if (array_key_exists('lookuptable2', $data)) {
            $entity->setLookuptable2($data['lookuptable2']);
        }
        if (array_key_exists('baseattributes', $data)) {
            $entity->setBaseattributes($data['baseattributes']);
        }
        if (array_key_exists('template', $data)) {
            $entity->setTemplate($data['template']);
        }
        if (array_key_exists('repeatable', $data)) {
            $entity->setRepeatable($data['repeatable'] !== null ? (bool) $data['repeatable'] : null);
        }

        // Koppeling met ValidationRule (indien meegegeven als ID)
        if (!empty($data['validationrule_id'])) {
            $validationRule = $em->getRepository(V3Validationrules::class)->find($data['validationrule_id']);
            if ($validationRule) {
                $entity->setValidationrule($validationRule);
            }
        }

        // Audit-velden
        $userId = $data['created_by'] ?? 1; // Pas aan naar eventueel $this->getUser()->getId()
        $now = new \DateTime();

        $entity->setCreatedBy($userId);
        $entity->setCreatedAt($now);
        $entity->setUpdatedBy($userId);
        $entity->setUpdatedAt($now);

        $em->persist($entity);
        $em->flush();

        return $this->json(
            $entity,
            Response::HTTP_CREATED,
            [],
            self::SERIALIZATION_CONTEXT
        );
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, ?V3Attributes $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->toArray();

        // Update velden (enkel als ze in de request body zitten)
        if (array_key_exists('name', $data)) {
            $entity->setName($data['name']);
        }
        if (array_key_exists('description', $data)) {
            $entity->setDescription($data['description']);
        }
        if (array_key_exists('visibility', $data)) {
            $entity->setVisibility((bool) $data['visibility']);
        }
        if (array_key_exists('lookuptable', $data)) {
            $entity->setLookuptable($data['lookuptable']);
        }
        if (array_key_exists('lookuptable2', $data)) {
            $entity->setLookuptable2($data['lookuptable2']);
        }
        if (array_key_exists('baseattributes', $data)) {
            $entity->setBaseattributes($data['baseattributes']);
        }
        if (array_key_exists('template', $data)) {
            $entity->setTemplate($data['template']);
        }
        if (array_key_exists('repeatable', $data)) {
            $entity->setRepeatable($data['repeatable'] !== null ? (bool) $data['repeatable'] : null);
        }

        // Update ValidationRule relatie
        if (array_key_exists('validationrule_id', $data)) {
            if ($data['validationrule_id'] === null) {
                $entity->setValidationrule(null);
            } else {
                $validationRule = $em->getRepository(V3Validationrules::class)->find($data['validationrule_id']);
                if ($validationRule) {
                    $entity->setValidationrule($validationRule);
                }
            }
        }

        // Audit update-velden
        $userId = $data['updated_by'] ?? 1;
        $entity->setUpdatedBy($userId);
        $entity->setUpdatedAt(new \DateTime());

        $em->flush();

        return $this->json(
            $entity,
            Response::HTTP_OK,
            [],
            self::SERIALIZATION_CONTEXT
        );
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(?V3Attributes $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($entity);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
