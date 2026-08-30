<?php

namespace App\Controller\Api;

use App\Entity\V3AttributeContentTypes;
use App\Entity\V3Attributes;
use App\Entity\V3Contenttypes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v3/attributecontenttypes')]
class V3AttributeContentTypesController extends AbstractController
{
    #[Route('/{id}', name: 'app_api_v3_attribute_content_types_update', methods: ['PUT'])]
    public function updateAttributesForContentType(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $contentTypeRepo = $em->getRepository(V3Contenttypes::class);
        $attributeRepo = $em->getRepository(V3Attributes::class);
        $attributeContentTypeRepo = $em->getRepository(V3AttributeContentTypes::class);

        $contentType = $contentTypeRepo->find($id);

        if (!$contentType) {
            return $this->json(['error' => 'ContentType niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->toArray();

        // 1. Verwijder eerst alle bestaande koppelingen en voer direct de DB-delete uit
        $existingLinks = $attributeContentTypeRepo->findBy(['contentType' => $contentType]);
        foreach ($existingLinks as $link) {
            $em->remove($link);
        }

        // Zorg dat Doctrine het geheugen (UnitOfWork) vrijmaakt vóór we opnieuw toevoegen!
        $em->flush();

        // 2. Voeg de nieuwe koppelingen toe
        foreach ($data as $index => $item) {
            $attrId = $item['attribute_id'] ?? $item['attributeid'] ?? null;
            if (!$attrId) {
                continue;
            }

            $attribute = $attributeRepo->find($attrId);
            if ($attribute) {
                $relation = new V3AttributeContentTypes();
                $relation->setContentType($contentType);
                $relation->setAttribute($attribute);

                $displayOrder = $item['display_order'] ?? $item['displayOrder'] ?? ($index + 1);
                $relation->setDisplayOrder((int) $displayOrder);

                $em->persist($relation);
            }
        }

        // Sla de nieuwe relaties op
        $em->flush();

        return $this->json(
            ['message' => 'Attributen succesvol bijgewerkt voor ContentType ' . $id],
            Response::HTTP_OK
        );
    }
}
