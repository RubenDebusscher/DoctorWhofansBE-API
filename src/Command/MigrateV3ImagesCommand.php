<?php

namespace App\Command;

use App\Entity\V3Items;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

#[AsCommand(
    name: 'app:migrate-v3-images',
    description: 'Verplaatst bestaande V3Item afbeeldingen uit cdn/images/ naar cdn/items/{id}/ en werkt de database bij.'
)]
class MigrateV3ImagesCommand extends Command
{
    private string $cdnDir;

    public function __construct(
        private EntityManagerInterface $em,
        private Filesystem $filesystem,
        ParameterBagInterface $params
    ) {
        parent::__construct();
        $this->cdnDir = '/mnt/test/DoctorWhoFans/cdn';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Starten van de V3 Afbeeldingen Database Sync');

        $repo = $this->em->getRepository(V3Items::class);
        $items = $repo->findAll();

        $updatedCount = 0;
        $notFoundCount = 0;
        $skippedNull = 0;

        foreach ($items as $item) {
            $itemId = $item->getItemId();
            $itemName = 'Item #' . $itemId;

            if (method_exists($item, 'getItemName')) {
                $itemName = $item->getItemName();
            } elseif (method_exists($item, 'getTitle')) {
                $itemName = $item->getTitle();
            }

            $currentImage = null;
            if (method_exists($item, 'getItemImage')) {
                $currentImage = $item->getItemImage();
            } elseif (method_exists($item, 'getImage')) {
                $currentImage = $item->getImage();
            }

            if (!$currentImage || strtoupper(trim($currentImage)) === 'NULL') {
                $skippedNull++;
                continue;
            }

            // 1. Check of het bestand AL VERPLAATST IS naar cdn/items/{itemId}/
            $targetDir = sprintf('%s/items/%d', $this->cdnDir, $itemId);
            $alreadyMigratedFile = $this->findFileInDir($targetDir);

            if ($alreadyMigratedFile) {
                $fileName = basename($alreadyMigratedFile);
                $newRelativePath = sprintf('items/%d/%s', $itemId, $fileName);

                $this->updateItemPath($item, $newRelativePath);
                $updatedCount++;
                $io->text(sprintf('✓ [Reeds verplaatst] Item #%d (%s) DB updated naar: %s', $itemId, $itemName, $newRelativePath));
                continue;
            }

            // 2. Zoek het bestand als het nog wel in cdn/images/ staat
            $filenameOnly = basename($currentImage);
            $sourcePath = $this->findFileInDir($this->cdnDir . '/images/V3__Items', $filenameOnly);

            if (!$sourcePath) {
                $io->warning(sprintf('Afbeelding niet gevonden voor Item #%d (%s): %s', $itemId, $itemName, $filenameOnly));
                $notFoundCount++;
                continue;
            }

            // 3. Verplaatst alsnog en update DB
            if (!$this->filesystem->exists($targetDir)) {
                $this->filesystem->mkdir($targetDir, 0775);
            }

            $ext = pathinfo($sourcePath, PATHINFO_EXTENSION) ?: 'jpg';
            $newFileName = sprintf('cover.%s', strtolower($ext));
            $targetPath = sprintf('%s/%s', $targetDir, $newFileName);

            $this->filesystem->copy($sourcePath, $targetPath, true);
            $this->filesystem->remove($sourcePath);

            $newRelativePath = sprintf('items/%d/%s', $itemId, $newFileName);
            $this->updateItemPath($item, $newRelativePath);

            $updatedCount++;
            $io->text(sprintf('✓ Item #%d (%s): %s -> %s', $itemId, $itemName, $filenameOnly, $newRelativePath));
        }

        // Bewaar de DB-wijzigingen
        $this->em->flush();

        $io->newLine();
        $io->success(sprintf('Klaar! Database succesvol bijgewerkt voor %d items.', $updatedCount));

        return Command::SUCCESS;
    }

    private function updateItemPath(V3Items $item, string $path): void
    {
        if (method_exists($item, 'setItemImage')) {
            $item->setItemImage($path);
        } elseif (method_exists($item, 'setImage')) {
            $item->setImage($path);
        }
    }

    private function findFileInDir(string $dir, ?string $filename = null): ?string
    {
        if (!is_dir($dir)) {
            return null;
        }

        $finder = new Finder();
        $finder->files()->in($dir);

        if ($filename) {
            $finder->name($filename);
        }

        foreach ($finder as $file) {
            return $file->getRealPath();
        }

        return null;
    }
}
