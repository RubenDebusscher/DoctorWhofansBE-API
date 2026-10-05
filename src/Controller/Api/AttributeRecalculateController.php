<?php
namespace App\Controller\Api;

use App\Repository\V3AttributesRepository;
use App\Repository\V3ItemsRepository;
use App\Service\CalculatedExpressionEvaluator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class AttributeRecalculateController extends AbstractController
{
    #[Route('/api/v3/attributes/{id}/recalculate-all', methods: ['POST'])]
    public function recalculateAllForAttribute(
        int $id,
        V3AttributesRepository $attributesRepository,
        V3ItemsRepository $itemsRepository,
        CalculatedExpressionEvaluator $evaluator
    ): JsonResponse {
        $definition = $attributesRepository->find($id);

        if (!$definition) {
            return $this->json(['error' => 'Attribuut niet gevonden'], 404);
        }

        // Optioneel: Haal alleen de items op die dit contentType/attribuut daadwerkelijk hebben
        $items = $itemsRepository->findAll();
        $updatedCount = 0;

        foreach ($items as $item) {
            // Voer herberekening uit
            $evaluator->recalculateAndSaveForItem($item);
            $updatedCount++;
        }

        return $this->json(
            [
            'success' => true,
            'message' => "Succesvol {$updatedCount} items herberekend voor attribuut '{$definition->getName()}'."
            ]
        );
    }
}
