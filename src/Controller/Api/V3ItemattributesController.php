<?php

namespace App\Controller\Api;

use App\Entity\V3Itemattributes;
use App\Repository\V3ItemattributesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\V3Attributes;
use App\Entity\V3Items;

#[Route('/api/v3/itemattributes')]
class V3ItemattributesController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(V3ItemattributesRepository $repository): JsonResponse
    {
        return $this->json(
            $repository->findAll(), Response::HTTP_OK, [], [
                'groups' => ['v3_itemattributes:read']
            ]
        );
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(?V3Itemattributes $entity): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $entity, Response::HTTP_OK, [], [
                'groups' => ['v3_itemattributes:read']
            ]
        );
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->toArray();
        $entity = new V3Itemattributes();

        $this->mapDataToEntity($data, $entity, $em);

        $user = $this->getUser();
        if ($user) {
            $entity->setCreatedBy($user);
            $entity->setUpdatedBy($user);
        }

        $em->persist($entity);
        $em->flush();

        return $this->formatResponse($entity, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, ?V3Itemattributes $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->toArray();
        $this->mapDataToEntity($data, $entity, $em);

        $user = $this->getUser();
        if ($user) {
            $entity->setUpdatedBy($user);
        }

        $em->flush();

        return $this->formatResponse($entity, Response::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(?V3Itemattributes $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($entity);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Hulpmethode om de JSON-response veilig op te bouwen zonder Circular Reference.
     */
    private function formatResponse(V3Itemattributes $entity, int $status): JsonResponse
    {
        $itemId = null;
        if (method_exists($entity, 'getItem') && $entity->getItem() !== null) {
            $itemId = $entity->getItem()->getItemid();
        }

        $attrId = null;
        if (method_exists($entity, 'getAttributeid') && $entity->getAttributeid() !== null) {
            $attr = $entity->getAttributeid();
            $attrId = is_object($attr) && method_exists($attr, 'getAttributeid') ? $attr->getAttributeid() : $attr;
        }

        return $this->json(
            [
            'id'          => $entity->getItemattributevalueid(),
            'itemid'      => $itemId,
            'attributeid' => $attrId,
            'value'       => $entity->getValue(),
            'numbervalue' => $entity->getNumbervalue(),
            'boolvalue'   => method_exists($entity, 'isBoolvalue') ? $entity->isBoolvalue() : $entity->getBoolvalue(),
            'lookupvalue' => $entity->getLookupvalue(),
            'datevalue'   => $entity->getDatevalue() ? $entity->getDatevalue()->format('Y-m-d H:i:s') : null,
            ], $status
        );
    }

    private function mapDataToEntity(array $data, V3Itemattributes $entity, EntityManagerInterface $em): void
    {
        // --- 1. ITEM KOPPELING (itemid) ---
        $itemId = $data['itemid'] ?? $data['itemID'] ?? $data['item'] ?? null;
        if (is_array($itemId)) {
            $itemId = $itemId['itemid'] ?? $itemId['id'] ?? null;
        }

        if ($itemId !== null) {
            $itemEntity = $em->getRepository(V3Items::class)->find($itemId);

            if ($itemEntity && method_exists($entity, 'setItem')) {
                $entity->setItem($itemEntity);
            } elseif ($itemEntity && method_exists($entity, 'setItemid')) {
                $entity->setItemid($itemEntity);
            } elseif (method_exists($entity, 'setItemid')) {
                $entity->setItemid($itemId);
            }
        }

        // --- 2. ATTRIBUUT KOPPELING (attributeid) ---
        $attrId = $data['attributeid'] ?? $data['attributeID'] ?? $data['attribute'] ?? null;
        if (is_array($attrId)) {
            $attrId = $attrId['attributeid'] ?? $attrId['id'] ?? null;
        }

        $attribute = null;
        if ($attrId !== null) {
            $attribute = $em->getRepository(V3Attributes::class)->find($attrId);

            if ($attribute && method_exists($entity, 'setAttributeid')) {
                $entity->setAttributeid($attribute);
            } elseif ($attribute && method_exists($entity, 'setAttribute')) {
                $entity->setAttribute($attribute);
            }
        } else {
            // BELANGRIJK VOOR PUT: Als attributeid niet meegestuurd wordt in de payload,
            // halen we de gekoppelde V3Attributes entiteit direct uit het bestaande $entity object!
            if (method_exists($entity, 'getAttributeid')) {
                $attribute = $entity->getAttributeid();
            } elseif (method_exists($entity, 'getAttribute')) {
                $attribute = $entity->getAttribute();
            }
        }

        // --- 3. WAARDE TOEWIJZEN OP BASIS VAN VALIDATIONRULE LABEL EN SPECIFIEKE KEYS ---

        // Reset alle kolommen om te voorkomen dat oude/dubbele waarden achterblijven
        $entity->setValue(null);
        $entity->setNumbervalue(null);
        $entity->setBoolvalue(null);
        $entity->setLookupvalue(null);
        $entity->setDatevalue(null);

        // Bepaal de 'label' (het type invoer zoals 'number', 'lookup', etc.)
        $ruleLabel = '';
        if ($attribute && method_exists($attribute, 'getValidationrule')) {
            $valRule = $attribute->getValidationrule();

            if (is_object($valRule)) {
                if (method_exists($valRule, 'getLabel')) {
                    $ruleLabel = $valRule->getLabel();
                } elseif (method_exists($valRule, 'getName')) {
                    $ruleLabel = $valRule->getName();
                } elseif (method_exists($valRule, 'getType')) {
                    $ruleLabel = $valRule->getType();
                } elseif (method_exists($valRule, 'getValue')) {
                    $ruleLabel = $valRule->getValue();
                } elseif (method_exists($valRule, '__toString')) {
                    $ruleLabel = (string) $valRule;
                }
            } elseif (is_string($valRule)) {
                $ruleLabel = $valRule;
            }
        }

        $typeStr = strtolower((string) $ruleLabel);

        // VOORKEUR 1: Als de JSON payload expliciet een specifiek veld bevat
        if (array_key_exists('numbervalue', $data) && $data['numbervalue'] !== null && $data['numbervalue'] !== '') {
            $entity->setNumbervalue((float) $data['numbervalue']);
            return;
        }
        if (array_key_exists('lookupvalue', $data) && $data['lookupvalue'] !== null && $data['lookupvalue'] !== '') {
            $entity->setLookupvalue((int) $data['lookupvalue']);
            return;
        }
        if (array_key_exists('datevalue', $data) && $data['datevalue'] !== null && $data['datevalue'] !== '') {
            $entity->setDatevalue(new \DateTime((string) $data['datevalue']));
            return;
        }
        if (array_key_exists('boolvalue', $data) && $data['boolvalue'] !== null) {
            $entity->setBoolvalue((bool) $data['boolvalue']);
            return;
        }

        // VOORKEUR 2: Fallback via generic 'value' veld op basis van de validation rule label
        $rawVal = $data['value'] ?? null;

        if ($rawVal !== null && $rawVal !== '') {
            if (str_contains($typeStr, 'number') || str_contains($typeStr, 'numeric') || str_contains($typeStr, 'int') || str_contains($typeStr, 'float')) {
                $entity->setNumbervalue((float) $rawVal);
            } elseif (str_contains($typeStr, 'bool')) {
                $entity->setBoolvalue((bool) $rawVal);
            } elseif (str_contains($typeStr, 'date') || str_contains($typeStr, 'time')) {
                $entity->setDatevalue(new \DateTime((string) $rawVal));
            } elseif (str_contains($typeStr, 'lookup') || str_contains($typeStr, 'select')) {
                $entity->setLookupvalue((int) $rawVal);
            } else {
                $entity->setValue((string) $rawVal);
            }
        }
    }
}
