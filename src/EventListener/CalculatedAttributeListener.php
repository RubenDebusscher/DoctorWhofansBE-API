<?php
namespace App\EventListener;

use App\Entity\V3Itemattribute;
use App\Service\CalculatedExpressionEvaluator;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: V3ItemattributesRepository::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: V3ItemattributesRepository::class)]
class CalculatedAttributeListener
{
    public function __construct(
        private CalculatedExpressionEvaluator $evaluator
    ) {
    }

    public function postPersist(V3ItemattributesRepository $attribute, PostPersistEventArgs $event): void
    {
        $this->processCascade($attribute);
    }

    public function postUpdate(V3ItemattributesRepository $attribute, PostUpdateEventArgs $event): void
    {
        $this->processCascade($attribute);
    }

    private function processCascade(V3ItemattributesRepository $attribute): void
    {
        $item = $attribute->getItem();
        if (!$item) { return;
        }

        // 1. Altijd het huidige item evalueren/upserten
        $this->evaluator->recalculateAndSaveForItem($item);

        // 2. Als dit attribuut een lookup / koppeling is, herbereken de doel-parent
        if ($attribute->getLookupvalue() !== null) {
            $targetItem = $this->evaluator->findItemById($attribute->getLookupvalue());
            if ($targetItem) {
                // De evaluator controleert zelf of dit specifieke parent-type
                // berekende attributen heeft en voegt/wijzigt ze zo nodig
                $this->evaluator->recalculateAndSaveForItem($targetItem);
            }
        }
    }
}
