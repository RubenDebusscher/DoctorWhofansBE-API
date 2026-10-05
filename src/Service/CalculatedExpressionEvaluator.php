<?php

namespace App\Service;

use App\Entity\Code;
use App\Entity\V3Items;
use App\Entity\V3Attributes;
use App\Repository\CodeRepository;
use App\Repository\V3ItemattributesRepository;
use App\Repository\V3ItemsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class CalculatedExpressionEvaluator
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private V3ItemattributesRepository $itemAttributeRepository,
        private V3ItemsRepository $itemRepository,
        private CodeRepository $codeRepository
    ) {
    }

    /**
     * Publieke entry-point aangeroepen vanuit Controllers / Listeners
     */
    public function recalculateAndSaveForItem(V3Items $item, bool $isParent = false): void
    {
        $attributes = $item->getItemAttributes() ?? [];

        foreach ($attributes as $itemAttributeValue) {
            $definition = $itemAttributeValue->getAttributeid();

            if ($definition && $definition->getValidationrule()?->getLabel() === 'calculated') {
                $calculatedResult = $this->evaluateDefinitionForItem($item, $definition);
                $itemAttributeValue->setCalculatedvalue($calculatedResult);
            }
        }

        // 1. Sla de berekende waarden EERST op in de database
        $this->entityManager->flush();

        // 2. Alleen als dit een CHILD is (geen parent), triggeren we de parent.
        if (!$isParent) {
            $this->triggerParentRecalculation($item);
        }
    }

    public function triggerParentRecalculation(V3Items $item): void
    {
        $attributes = $item->getItemAttributes() ?? [];

        foreach ($attributes as $itemAttribute) {
            $definition = $itemAttribute->getAttributeid();

            if (!$definition) {
                continue;
            }

            $name = strtolower($definition->getName() ?? '');

            if (str_contains($name, 'parent') || str_contains($name, 'linked') || str_contains($name, 'relation') || str_contains($name, 'serial')) {

                $rawParentId = $itemAttribute->getLookupvalue()
                    ?: $itemAttribute->getNumbervalue()
                    ?: $itemAttribute->getValue();

                $parentId = is_numeric($rawParentId) ? (int) $rawParentId : 0;

                // Beveiliging tegen self-referencing loops
                if ($parentId > 0 && $parentId !== $item->getId()) {
                    $parentItem = $this->itemRepository->find($parentId);

                    if ($parentItem instanceof V3Items) {
                        $this->entityManager->refresh($parentItem);
                        $this->recalculateAndSaveForItem($parentItem, true);
                    }
                }
            }
        }
    }

    public function evaluateDefinitionForItem(V3Items $item, V3Attributes $definition): string
    {
        $expression = $definition->getTemplate();

        if (!$expression) {
            return '';
        }

        $variables = $this->parseBaseAttributes($definition->getBaseattributes(), $item);
        $expression = $this->substituteVariables($expression, $variables, $item);
        $expression = $this->evaluateRecursiveHelpers($expression, $item);

        return trim($expression);
    }

    private function substituteVariables(string $template, array $variables, V3Items $item): string
    {
        $result = $template;
        foreach ($variables as $placeholder => $data) {
            $valueToInsert = '';

            if ($data['type'] === 'system') {
                // Herkenning van de Item ID systeemvariabele
                if (in_array(strtolower($data['key']), ['__item_id__', '__id__', '__itemid__'])) {
                    $valueToInsert = (string) $item->getId();
                } else {
                    $valueToInsert = '"' . $data['key'] . '"';
                }
            } elseif ($data['mode'] === 'id') {
                $valueToInsert = (string) $data['attribute_id'];
            } else {
                $valueToInsert = (string) ($data['value'] ?? '0');
            }

            $result = str_replace($placeholder, $valueToInsert, $result);
        }

        return $result;
    }

    private function evaluateRecursiveHelpers(string $expression, V3Items $item): string
    {
        $maxIterations = 20;

        while ($maxIterations-- > 0) {
            $hasChanges = false;

            $helperPattern = '/helper->([a-z0-9_]+)\(([^()]*?)\)/i';
            if (preg_match($helperPattern, $expression, $matches)) {
                $fullMatch = $matches[0];
                $funcName  = $matches[1];
                $rawArgs   = $matches[2];

                $args = array_map(fn($arg) => trim($arg, ' "^\''), explode(',', $rawArgs));
                $result = $this->dispatchHelper($funcName, $args, $item);

                $expression = str_replace($fullMatch, (string)$result, $expression);
                $hasChanges = true;
                continue;
            }

            $ifPattern = '/if\(([^()]+?)\)/i';
            if (preg_match($ifPattern, $expression, $matches)) {
                $fullMatch = $matches[0];
                $rawCases  = $matches[1];

                $result = $this->evaluateMultiCaseIf($rawCases);

                $expression = str_replace($fullMatch, (string)$result, $expression);
                $hasChanges = true;
                continue;
            }

            if (!$hasChanges) {
                break;
            }
        }

        return $expression;
    }

    private function evaluateMultiCaseIf(string $rawCases): string
    {
        $cases = explode(',', $rawCases);

        foreach ($cases as $case) {
            $parts = explode(':', trim($case), 2);

            if (count($parts) === 1) {
                return trim($parts[0]);
            }

            $condition = trim($parts[0]);
            $value     = trim($parts[1]);

            $condition = preg_replace('/^\((.*)\)$/', '$1', $condition);

            if ($this->evaluateCondition($condition)) {
                return $value;
            }
        }

        return '0';
    }

    private function evaluateCondition(string $condition): bool
    {
        if (preg_match('/^(\d+)\s*(>|<|>=|<=|==|!=)\s*(\d+)$/', $condition, $m)) {
            $left  = (int) $m[1];
            $op    = $m[2];
            $right = (int) $m[3];

            return match ($op) {
                '>'  => $left > $right,
                '<'  => $left < $right,
                '>=' => $left >= $right,
                '<=' => $left <= $right,
                '==' => $left === $right,
                '!=' => $left !== $right,
                default => false
            };
        }

        return (bool) (int) $condition;
    }

    private function dispatchHelper(string $functionName, array $args, V3Items $item): mixed
    {
        return match ($functionName) {
            'count_children'   => $this->itemAttributeRepository->countChildrenByParentAttribute($item->getId(), $args[1] ?? ''),
            'sum_children'     => $this->itemAttributeRepository->sumChildAttributeValue($item->getId(), (int) ($args[0] ?? 0)),
            'display_duration' => $this->helperDisplayDuration($args),
            'resolve_path', 'path' => $this->prepareAndResolvePathFromArgs($args, $item),
            default => $args[0] ?? 0
        };
    }

    /**
     * Verwerkt de helper-parameters: [itemId, codeKey, separator, displayMode]
     */
    private function prepareAndResolvePathFromArgs(array $args, V3Items $defaultItem): string
    {
        $rawItemId   = trim($args[0] ?? '', ' "\'');
        $codeKey     = trim($args[1] ?? '', ' "\'');
        $separator   = isset($args[2]) ? trim($args[2], ' "\'') : ' > ';
        $displayMode = isset($args[3]) ? trim($args[3], ' "\'') : 'name'; // Options: 'name', 'id', 'both'

        // 1. Bepaal het start-item (via meegegeven ID of fallback op huidig item)
        $targetItem = $defaultItem;
        if (is_numeric($rawItemId) && (int)$rawItemId > 0) {
            $foundItem = $this->itemRepository->find((int)$rawItemId);
            if ($foundItem instanceof V3Items) {
                $targetItem = $foundItem;
            }
        }

        // Fallback voor het geval de codeKey als 1e argument is meegegeven
        if (empty($codeKey) && !is_numeric($rawItemId)) {
            $codeKey = $rawItemId;
        }

        if (empty($codeKey)) {
            return '';
        }

        // 2. Haal de Code-entiteit en de JSON-parameters op
        $codeEntity = $this->codeRepository->findOneBy(['codeValue' => $codeKey]);
        if (!$codeEntity) {
            return '';
        }

        $parameters = $codeEntity->getParameters();
        $config = is_string($parameters) ? json_decode($parameters, true) : $parameters;

        if (!is_array($config)) {
            return '';
        }

        // 3. Bereken het pad
        return $this->helperResolvePath($targetItem, $config, $separator ?: ' > ', $displayMode);
    }

    /**
     * Berekent het volledige pad als string op basis van de JSON config array.
     * Werkt dynamisch vanaf elk niveau (MULTIPART, SERIAL, SEASON, SHOW).
     */
    public function helperResolvePath(
        V3Items $startItem,
        array $config,
        string $separator = ' > ',
        string $displayMode = 'name'
    ): string {
        $pathParts = [];
        $currentItem = $startItem;

        // 1. Zoek de scope die matcht met het type van het startItem
        $currentScope = $this->findScopeForStartItem($startItem, $config);

        if (!$currentScope) {
            $currentScope = $config;
        }

        while ($currentScope !== null && $currentItem !== null) {
            $levelKey = array_key_first($currentScope);
            if (!$levelKey || !is_array($currentScope[$levelKey])) {
                break;
            }

            $levelConfig = $currentScope[$levelKey];

            // Bepaal waarde op basis van $displayMode
            $nodeValue = match (strtolower($displayMode)) {
                'id'    => (string) $currentItem->getId(),
                'both'  => $currentItem->getDisplayName() . ' (' . $currentItem->getId() . ')',
                default => $currentItem->getDisplayName(), // 'name'
            };

            array_unshift($pathParts, $nodeValue);

            $attributeId = $levelConfig['ATTRIBUTEID'] ?? null;
            if (empty($attributeId) || empty($levelConfig['PARENT'])) {
                break;
            }

            $parentId = $currentItem->getParentLookupValue((int) $attributeId);
            if (!$parentId || (int)$parentId === (int)$currentItem->getId()) {
                break;
            }

            $currentItem = $this->itemRepository->find($parentId);
            $currentScope = $levelConfig['PARENT'];
        }

        $result = implode($separator, array_unique($pathParts));

        if ($displayMode === 'id' && $separator === '/') {
            return '/' . $result . '/';
        }

        return $result;
    }

    /**
     * Zoekt recursief in de JSON naar de scope die matcht met de codeValue van het Item Type
     */
    private function findScopeForStartItem(V3Items $item, array $config): ?array
    {
        $typeCode = $item->getType();
        if (!$typeCode) {
            return null;
        }

        $itemTypeCodeValue = strtoupper((string) ($typeCode->getCodeValue() ?? $typeCode->getValue()));
        $currentKey = array_key_first($config);

        if (!$currentKey) {
            return null;
        }

        if (strtoupper((string) $currentKey) === $itemTypeCodeValue) {
            return $config;
        }

        if (isset($config[$currentKey]['PARENT']) && is_array($config[$currentKey]['PARENT'])) {
            return $this->findScopeForStartItem($item, $config[$currentKey]['PARENT']);
        }

        return null;
    }

    private function helperDisplayDuration(array $args): string
    {
        $totalSeconds = (int) ($args[0] ?? 0);

        if ($totalSeconds <= 0) {
            return '00h 00m 00s';
        }

        $days    = (int) floor($totalSeconds / 86400);
        $hours   = (int) floor(($totalSeconds % 86400) / 3600);
        $minutes = (int) floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        $formattedTime = sprintf('%02dh %02dm %02ds', $hours, $minutes, $seconds);

        if ($days > 0) {
            return sprintf('%dd ', $days) . $formattedTime;
        }

        return $formattedTime;
    }

    private function parseBaseAttributes(?string $json, V3Items $item): array
    {
        $baseAttributes = json_decode($json ?? '[]', true) ?? [];
        $variables = [];

        foreach ($baseAttributes as $varConfig) {
            $index = $varConfig['index'] ?? null;
            $id = $varConfig['id'] ?? null;
            $mode = $varConfig['mode'] ?? 'val';

            if (!$index) {
                continue;
            }

            $placeholder = '%' . $index;

            if (is_string($id) && str_starts_with($id, '__')) {
                $variables[$placeholder] = ['type' => 'system', 'key' => $id, 'mode' => $mode];
            } else {
                $val = $this->resolveVariableValue($item, (int)$id);
                $variables[$placeholder] = [
                    'type' => 'attribute',
                    'attribute_id' => (int)$id,
                    'mode' => $mode,
                    'value' => $val
                ];
            }
        }

        return $variables;
    }

    private function resolveVariableValue(V3Items $item, int $attributeId): ?string
    {
        $attributes = $item->getItemAttributes() ?? [];

        foreach ($attributes as $attr) {
            $def = $attr->getAttributeid();
            $defId = $def instanceof V3Attributes ? $def->getId() : $def;

            if ((int)$defId === $attributeId) {
                return $attr->getNumbervalue() ?? $attr->getValue();
            }
        }

        return null;
    }
}
