<?php
namespace App\Serializer;

use App\Entity\V3Itemattributes;
use App\Entity\V3Items;
use App\Entity\Code;
use App\Entity\ManagementPages; // Zorg dat deze is geïmporteerd
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class V3ItemAttributesNormalizer implements NormalizerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof V3Itemattributes;
    }

    /**
     * @param V3Itemattributes $object
     */
    public function normalize(mixed $object, ?string $format = null, array $context = []): array
    {
        $data = [
            'itemattributevalueid' => $object->getItemattributevalueid(),
            'value' => $object->getValue(),
            'numbervalue' => $object->getNumbervalue(),
            'datevalue' => $object->getDatevalue()?->format(\DateTime::ATOM),
            'boolvalue' => $object->getBoolvalue(),
            'lookupvalue' => $object->getLookupvalue(),
            'lookupvalue2' => $object->getLookupvalue2(),
            'calculatedvalue' => $object->getCalculatedvalue(),
        ];

        $attribute = $object->getAttributeid();
        if ($attribute) {
            $data['attributeid'] = [
                'attributeid' => $attribute->getAttributeid(),
                'name' => $attribute->getName(),
                'description' => $attribute->getDescription(),
                'lookuptable' => $attribute->getLookuptable(),
                'lookuptable2' => $attribute->getLookuptable2(),
                'visibility' => method_exists($attribute, 'isVisibility') ? $attribute->isVisibility() : ($attribute->getVisibility() ?? true),
                'validationrule' => $attribute->getValidationrule() ? [
                    'id' => $attribute->getValidationrule()->getId(),
                    'codeValue' => $attribute->getValidationrule()->getCodeValue(),
                    'label' => $attribute->getValidationrule()->getLabel(),
                ] : null,
            ];

            $validationRule = $attribute->getValidationrule()?->getCodeValue();

            if ($validationRule === 'lookup') {
                // --- 1. Verwerk lookupvalue ---
                $lookupTableRaw = $attribute->getLookuptable();
                $lookupValueId = $object->getLookupvalue();

                if ($lookupTableRaw && $lookupValueId) {
                    $parts = explode('?', $lookupTableRaw);
                    $entityAlias = strtolower(trim($parts[0]));

                    $entityClass = match ($entityAlias) {
                        'items', 'v3items' => V3Items::class,
                        'codes', 'code' => Code::class,
                        default => null,
                    };

                    if ($entityClass) {
                        $targetObject = $this->entityManager->getRepository($entityClass)->find($lookupValueId);

                        if ($targetObject) {
                            $name = method_exists($targetObject, 'getName') ? $targetObject->getName() :
                                    (method_exists($targetObject, 'getLabel') ? $targetObject->getLabel() : null);

                            $slug = method_exists($targetObject, 'getSlug') ? $targetObject->getSlug() : null;

                            // Zoek in ManagementPages of er een pagina gekoppeld is aan dit item
                            // (Pas 'item' of 'v3Item' eventueel aan naar de exacte veldnaam in jouw ManagementPages entiteit)
                            $managementPage = $this->entityManager->getRepository(ManagementPages::class)->findOneBy(
                                [
                                'apiItem' => $targetObject // of bijv. 'itemId' => $lookupValueId afhankelijk van je relatie/kolom
                                ]
                            );

                            // Bepaal de link op basis van de gevonden management page (bijv. slug of url)
                            $link = null;
                            if ($managementPage) {
                                $link = method_exists($managementPage, 'getLink') ? $managementPage->getLink() : null;
                            }

                            $data['resolvedLookup'] = [
                                'id' => $lookupValueId,
                                'name' => $name,
                                'slug' => $slug,
                                'link' => $link
                            ];
                        }
                    }
                }

                // --- 2. Verwerk lookupvalue2 ---
                $lookupTable2Raw = $attribute->getLookuptable2();
                $lookupValueId2 = $object->getLookupvalue2();

                if ($lookupTable2Raw && $lookupValueId2) {
                    $parts2 = explode('?', $lookupTable2Raw);
                    $entityAlias2 = strtolower(trim($parts2[0]));

                    $entityClass2 = match ($entityAlias2) {
                        'codes', 'code' => Code::class,
                        'items', 'v3items' => V3Items::class,
                        default => null,
                    };

                    if ($entityClass2) {
                        $targetObject2 = $this->entityManager->getRepository($entityClass2)->find($lookupValueId2);

                        if ($targetObject2) {
                            $name2 = method_exists($targetObject2, 'getLabel') ? $targetObject2->getLabel() :
                                     (method_exists($targetObject2, 'getName') ? $targetObject2->getName() :
                                     (method_exists($targetObject2, 'getCodeValue') ? $targetObject2->getCodeValue() : null));

                            $data['resolvedLookup2'] = [
                                'id' => $lookupValueId2,
                                'name' => $name2,
                            ];
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            V3Itemattributes::class => true,
        ];
    }
}
