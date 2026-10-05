<?php

namespace App\Controller\Api;

use App\Entity\V3Attributes;
use App\Entity\V3Itemattributes;
use App\Entity\V3Items;
use App\Repository\V3ItemattributesRepository;
use App\Service\CalculatedExpressionEvaluator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v3/itemattributes')]
class V3ItemattributesController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(V3ItemattributesRepository $repository): JsonResponse
    {
        $entities = $repository->findAll();
        $data = array_map(fn($entity) => $this->formatEntityArray($entity), $entities);

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(?V3Itemattributes $entity): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($this->formatEntityArray($entity), Response::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, CalculatedExpressionEvaluator $evaluator): JsonResponse
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

        // Herberekening uitvoeren op het gekoppelde hoofditem
        $item = $entity->getItem();
        if ($item) {
            $evaluator->recalculateAndSaveForItem($item);
        }

        $em->flush();

        return $this->json($this->buildResponseData($entity, $em), Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, ?V3Itemattributes $entity, EntityManagerInterface $em, CalculatedExpressionEvaluator $evaluator): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->toArray();
        $this->mapDataToEntity($data, $entity, $em, false);

        $user = $this->getUser();
        if ($user) {
            $entity->setUpdatedBy($user);
        }

        // Herberekening uitvoeren op het gekoppelde hoofditem
        $item = $entity->getItem();
        if ($item) {
            $evaluator->recalculateAndSaveForItem($item);
        }

        $em->flush();

        return $this->json($this->buildResponseData($entity, $em), Response::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(?V3Itemattributes $entity, EntityManagerInterface $em, CalculatedExpressionEvaluator $evaluator): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $item = $entity->getItem();

        $em->remove($entity);

        if ($item) {
            $evaluator->recalculateAndSaveForItem($item);
        }

        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Bouwt het responsobject op met de getters van het V3Items entiteit.
     */
    private function buildResponseData(V3Itemattributes $entity, EntityManagerInterface $em): array
    {
        $item = $entity->getItem();
        $itemData = null;

        if ($item) {
            // Forceer Doctrine om de nieuwste relaties/waarden te synchroniseren
            $em->refresh($item);

            $total = method_exists($item, 'getTotalAttributesCount')
                ? $item->getTotalAttributesCount()
                : count($item->getItemAttributes());

            $filled = method_exists($item, 'getFilledAttributesCount')
                ? $item->getFilledAttributesCount()
                : 0;

            $percentage = method_exists($item, 'getCompletionPercentage')
                ? $item->getCompletionPercentage()
                : ($total > 0 ? (int) round(($filled / $total) * 100) : 0);

            $itemData = [
                'itemid'                 => $item->getItemid(),
                'totalAttributesCount'  => $total,
                'filledAttributesCount' => $filled,
                'completionPercentage'  => $percentage,
            ];
        }

        return [
            'attribute' => $this->formatEntityArray($entity),
            'item'      => $itemData,
        ];
    }

    private function formatEntityArray(V3Itemattributes $entity): array
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

        $dateValue = $entity->getDatevalue();
        if ($dateValue instanceof \DateTimeInterface) {
            $dateValue = $dateValue->format('Y-m-d H:i:s');
        }

        return [
            'id'              => $entity->getItemattributevalueid(),
            'itemid'          => $itemId,
            'attributeid'     => $attrId,
            'value'           => $entity->getValue(),
            'numbervalue'     => $entity->getNumbervalue(),
            'boolvalue'       => method_exists($entity, 'isBoolvalue') ? $entity->isBoolvalue() : $entity->getBoolvalue(),
            'lookupvalue'     => $entity->getLookupvalue(),
            'lookupvalue2'    => $entity->getLookupvalue2(),
            'calculatedvalue' => $entity->getCalculatedvalue(),
            'datevalue'       => $dateValue,
        ];
    }

    private function mapDataToEntity(array $data, V3Itemattributes $entity, EntityManagerInterface $em, bool $isNew = true): void
    {
        // 1. Item koppeling
        $itemId = $data['itemid'] ?? $data['itemID'] ?? $data['item'] ?? null;
        if (is_array($itemId)) {
            $itemId = $itemId['itemid'] ?? $itemId['id'] ?? null;
        }

        if ($itemId !== null) {
            $itemEntity = $em->getRepository(V3Items::class)->find($itemId);
            if ($itemEntity && method_exists($entity, 'setItem')) {
                $entity->setItem($itemEntity);
            }
        }

        // 2. Attribuut koppeling
        $attrId = $data['attributeid'] ?? $data['attributeID'] ?? $data['attribute'] ?? null;
        if (is_array($attrId)) {
            $attrId = $attrId['attributeid'] ?? $attrId['id'] ?? null;
        }

        $attribute = null;
        if ($attrId !== null) {
            $attribute = $em->getRepository(V3Attributes::class)->find($attrId);
            if ($attribute && method_exists($entity, 'setAttributeid')) {
                $entity->setAttributeid($attribute);
            }
        } else if (method_exists($entity, 'getAttributeid')) {
            $attribute = $entity->getAttributeid();
        }

        // 3. Waarde toewijzen
        if ($isNew) {
            $entity->setValue(null);
            $entity->setNumbervalue(null);
            $entity->setBoolvalue(null);
            $entity->setLookupvalue(null);
            $entity->setLookupvalue2(null);
            $entity->setDatevalue(null);
            $entity->setCalculatedvalue(null);
        }

        if (array_key_exists('calculatedvalue', $data) && $data['calculatedvalue'] !== null) {
            $entity->setCalculatedvalue((string) $data['calculatedvalue']);
            return;
        }
        if (array_key_exists('numbervalue', $data) && $data['numbervalue'] !== null && $data['numbervalue'] !== '') {
            $entity->setNumbervalue((float) $data['numbervalue']);
            return;
        }
        if (array_key_exists('lookupvalue', $data) && $data['lookupvalue'] !== null && $data['lookupvalue'] !== '') {
            $entity->setLookupvalue((int) $data['lookupvalue']);
            if (array_key_exists('lookupvalue2', $data) && $data['lookupvalue2'] !== null && $data['lookupvalue2'] !== '') {
                $entity->setLookupvalue2((int) $data['lookupvalue2']);
            }
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

        // Fallback toewijzing via 'value'
        $ruleLabel = '';
        if ($attribute && method_exists($attribute, 'getValidationrule')) {
            $valRule = $attribute->getValidationrule();
            if (is_object($valRule) && method_exists($valRule, 'getLabel')) {
                $ruleLabel = $valRule->getLabel();
            } elseif (is_string($valRule)) {
                $ruleLabel = $valRule;
            }
        }

        $typeStr = strtolower((string) $ruleLabel);
        $rawVal = $data['value'] ?? null;

        if ($rawVal !== null && $rawVal !== '') {
            if (str_contains($typeStr, 'calculated')) {
                $entity->setCalculatedvalue((string) $rawVal);
            } elseif (str_contains($typeStr, 'number') || str_contains($typeStr, 'numeric') || str_contains($typeStr, 'int') || str_contains($typeStr, 'float')) {
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
