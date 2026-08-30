<?php

namespace App\Service;

use App\Entity\V3Attributes;
use App\Entity\V3Itemattributes;

class AttributeValueManager
{
    public function assignValue(V3Itemattributes $itemAttrValue, mixed $rawPayload, V3Attributes $attribute): void
    {
        // 1. Haal de ruwe waarde uit de payload
        $inputValue = is_array($rawPayload) ? ($rawPayload['value'] ?? $rawPayload['val'] ?? reset($rawPayload)) : $rawPayload;

        // 2. Reset alle velden om datavuil / oude types op het record te voorkomen
        $itemAttrValue->setValue(null);
        $itemAttrValue->setNumbervalue(null);
        $itemAttrValue->setDatevalue(null);
        $itemAttrValue->setBoolvalue(null);
        $itemAttrValue->setLookupvalue(null);

        if ($inputValue === null || $inputValue === '') {
            return;
        }

        // 3. Haal het daadwerkelijke regel-type op via de doorverwijzing
        $ruleEntity = $attribute->getValidationrule(); // Geeft de gekoppelde V3Validationrules entiteit
        $ruleCode = $ruleEntity ? strtolower($ruleEntity->getName() ?? $ruleEntity->getType() ?? '') : '';

        // 4. Wijs toe strictly op basis van de doorverwezen regel
        match (true) {
            str_contains($ruleCode, 'int'),
            str_contains($ruleCode, 'number'),
            str_contains($ruleCode, 'numeric') => $itemAttrValue->setNumbervalue((int)$inputValue),

            str_contains($ruleCode, 'bool') => $itemAttrValue->setBoolvalue(filter_var($inputValue, FILTER_VALIDATE_BOOLEAN)),

            str_contains($ruleCode, 'date'),
            str_contains($ruleCode, 'time') => $itemAttrValue->setDatevalue(
                $inputValue instanceof \DateTimeInterface ? $inputValue : new \DateTime($inputValue)
            ),

            str_contains($ruleCode, 'lookup'),
            str_contains($ruleCode, 'select') => $itemAttrValue->setLookupvalue((int)$inputValue),

            default => $itemAttrValue->setValue((string)$inputValue),
        };
    }
}
