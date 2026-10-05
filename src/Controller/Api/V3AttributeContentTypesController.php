<?php

namespace App\Controller\Api;

use App\Entity\Code;
use App\Entity\V3Attributes;
use App\Entity\V3AttributeContentTypes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v3/attributecontenttypes')]
class V3AttributeContentTypesController extends AbstractController
{
    #[Route('/{id}', name: 'api_v3_attribute_content_types_update', methods: ['PUT'])]
    public function updateAttributesForContentType(
        int $id,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        // 1. Haal het Code object op (ContentType)
        $codeRepo = $em->getRepository(Code::class);
        $code = $codeRepo->find($id);

        if (!$code) {
            return new JsonResponse(
                [
                'title' => 'Not Found',
                'status' => Response::HTTP_NOT_FOUND,
                'detail' => sprintf('Code (ContentType) met ID %d is niet gevonden.', $id)
                ], Response::HTTP_NOT_FOUND
            );
        }

        // 2. Decodeer de JSON payload
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new JsonResponse(
                [
                'title' => 'Invalid JSON',
                'status' => Response::HTTP_BAD_REQUEST,
                'detail' => 'Ongeldige payload ontvangen. Een array met attributen wordt verwacht.'
                ], Response::HTTP_BAD_REQUEST
            );
        }

        $attributeContentTypeRepo = $em->getRepository(V3AttributeContentTypes::class);
        $attributeRepo = $em->getRepository(V3Attributes::class);

        // 3. Bepaal dynamisch de juiste koppel-veldnaam op V3AttributeContentTypes
        $metadata = $em->getClassMetadata(V3AttributeContentTypes::class);
        $codeFieldName = 'code';

        if (!$metadata->hasAssociation('code') && !$metadata->hasField('code')) {
            if ($metadata->hasAssociation('codeId')) {
                $codeFieldName = 'codeId';
            }
        }

        // 4. Verwijder alle bestaande relaties voor deze Code
        $existingLinks = $attributeContentTypeRepo->findBy([$codeFieldName => $code]);

        foreach ($existingLinks as $link) {
            $em->remove($link);
        }

        // Flush de deletes om unieke constraints/duplicaten te voorkomen
        $em->flush();

        // 5. Maak de nieuwe koppelingen aan met V3Attributes
        foreach ($data as $index => $item) {
            $attrId = $item['attributeId'] ?? $item['attribute_id'] ?? $item['attributeid'] ?? null;
            if (!$attrId) {
                continue;
            }

            $attribute = $attributeRepo->find($attrId);
            if ($attribute) {
                $relation = new V3AttributeContentTypes();

                // Koppel het Code object
                if (method_exists($relation, 'setCode')) {
                    $relation->setCode($code);
                } elseif (method_exists($relation, 'setCodeId')) {
                    $relation->setCodeId($code);
                }

                // Koppel het V3Attributes object
                $relation->setAttribute($attribute);

                $displayOrder = $item['displayOrder'] ?? $item['display_order'] ?? ($index + 1);
                $relation->setDisplayOrder((int) $displayOrder);

                $em->persist($relation);
            }
        }

        $em->flush();

        return new JsonResponse(
            [
            'status' => 'success',
            'message' => 'Attributen succesvol bijgewerkt voor dit content type.'
            ], Response::HTTP_OK
        );
    }
}
