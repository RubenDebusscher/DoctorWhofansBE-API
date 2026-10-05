<?php
namespace App\Command;

use App\Repository\V3AttributesRepository;
use App\Repository\V3ItemsRepository;
use App\Service\CalculatedExpressionEvaluator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:recalculate-attribute',
    description: 'Herbereken een berekend attribuut voor alle items die hier gebruik van maken.'
)]
class RecalculateAttributeCommand extends Command
{
    public function __construct(
        private V3AttributesRepository $attributesRepository,
        private V3ItemsRepository $itemsRepository,
        private CalculatedExpressionEvaluator $evaluator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('attributeId', InputArgument::REQUIRED, 'Het ID van het V3Attribute');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $attributeId = (int) $input->getArgument('attributeId');

        $definition = $this->attributesRepository->find($attributeId);
        if (!$definition) {
            $io->error("Attribute met ID {$attributeId} niet gevonden.");
            return Command::FAILURE;
        }

        // Haal alle items op
        $items = $this->itemsRepository->findAll();
        $io->progressStart(count($items));

        foreach ($items as $item) {
            // Evaluator rekent de nieuwe waarde uit en flusht de database
            $this->evaluator->recalculateAndSaveForItem($item);
            $io->progressAdvance();
        }

        $io->progressFinish();
        $io->success("Attribuut '{$definition->getName()}' is succesvol herberekend voor alle items!");

        return Command::SUCCESS;
    }
}
