<?php

namespace App\Command;

use App\Entity\V3Contenttypes;
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
    description: 'Verplaatst bestaande V3Item afbeeldingen uit cdn/images/ naar cdn/items/{type}/{id}/, ruimt lege mappen op en is veilig voor reruns.'
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
        $this->cdnDir = '/data/test/DoctorWhoFans/cdn';
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Starten van de V3 Afbeeldingen Database Sync & Cleanup');

        $repo = $this->em->getRepository(V3Items::class);
        $items = $repo->findAll();

        $updatedCount = 0;
        $notFoundCount = 0;
        $skippedCount = 0;

        $sourceBaseDir = $this->cdnDir . '/images/V3__Items';

        foreach ($items as $item) {
            $itemId = $item->getItemId();
            $itemName = 'Item #' . $itemId;

            // 1. Naam/Titel bepalen
            if (method_exists($item, 'getItemName')) {
                $itemName = $item->getItemName();
            } elseif (method_exists($item, 'getTitle')) {
                $itemName = $item->getTitle();
            }

            // 2. Type bepalen uit V3Contenttypes Entity of String
            $rawType = null;
            if (method_exists($item, 'getItemType')) {
                $rawType = $item->getItemType();
            } elseif (method_exists($item, 'getType')) {
                $rawType = $item->getType();
            }

            $typeString = 'General';
            if ($rawType instanceof V3Contenttypes) {
                $typeString = $rawType->getName() ?: 'General';
            } elseif (is_string($rawType) && !empty($rawType)) {
                $typeString = $rawType;
            }

            $itemType = ucfirst(strtolower(trim($typeString)));

            // 3. Huidige afbeelding ophalen uit DB
            $currentImage = null;
            if (method_exists($item, 'getItemImage')) {
                $currentImage = $item->getItemImage();
            } elseif (method_exists($item, 'getImage')) {
                $currentImage = $item->getImage();
            }

            if (!$currentImage || strtoupper(trim($currentImage)) === 'NULL') {
                $skippedCount++;
                continue;
            }

            // Target directory: cdn/items/{type}/{itemId}/
            $targetDir = sprintf('%s/items/%s/%d', $this->cdnDir, $itemType, $itemId);
            $legacyDir = sprintf('%s/items/%d', $this->cdnDir, $itemId);

            // 4. CHECK A: Staat de afbeelding AL in de juiste nieuwe map structuur?
            $alreadyMigratedFile = $this->findFileInDir($targetDir) ?? $this->findFileInDir($legacyDir);

            if ($alreadyMigratedFile) {
                $fileName = basename($alreadyMigratedFile);

                // Als hij nog in de legacy-map (/items/{id}/) stond, verplaatsen naar /items/{type}/{id}/
                if (str_contains($alreadyMigratedFile, sprintf('/items/%d/', $itemId))) {
                    if (!$this->filesystem->exists($targetDir)) {
                        $this->filesystem->mkdir($targetDir, 0775);
                    }
                    $targetPath = sprintf('%s/%s', $targetDir, $fileName);
                    $this->filesystem->rename($alreadyMigratedFile, $targetPath, true);

                    // Oude lege legacy map opruimen
                    $this->removeDirectoryIfEmpty($legacyDir);
                }

                $newRelativePath = sprintf('items/%s/%d/%s', $itemType, $itemId, $fileName);

                // DB alleen updaten als het pad gewijzigd is
                if ($currentImage !== $newRelativePath) {
                    $this->updateItemPath($item, $newRelativePath);
                    $updatedCount++;
                    $io->text(sprintf('✓ [Reeds verplaatst - DB Sync] Item #%d (%s) DB updated naar: %s', $itemId, $itemName, $newRelativePath));
                } else {
                    $skippedCount++;
                }
                continue;
            }

            // 5. CHECK B: Zoek het bestand in cdn/images/V3__Items (of submappen daarvan)
            $filenameOnly = basename($currentImage);
            $sourcePath = $this->findFileInDir($sourceBaseDir, $filenameOnly);

            if (!$sourcePath) {
                $io->warning(sprintf('Afbeelding niet gevonden voor Item #%d (%s): %s', $itemId, $itemName, $filenameOnly));
                $notFoundCount++;
                continue;
            }

            // 6. VERPLAATSEN & VERWIJDEREN BRONBESTAND
            if (!$this->filesystem->exists($targetDir)) {
                $this->filesystem->mkdir($targetDir, 0775);
            }

            $ext = pathinfo($sourcePath, PATHINFO_EXTENSION) ?: 'jpg';
            $newFileName = sprintf('cover.%s', strtolower($ext));
            $targetPath = sprintf('%s/%s', $targetDir, $newFileName);

            // Kopiëren en bron verwijderen
            $this->filesystem->copy($sourcePath, $targetPath, true);

            $sourceDir = dirname($sourcePath);
            $this->filesystem->remove($sourcePath);

            // Opruimen van de bronmap als deze nu leeg is
            $this->removeDirectoryIfEmpty($sourceDir);

            // 7. DB UPDATE
            $newRelativePath = sprintf('items/%s/%d/%s', $itemType, $itemId, $newFileName);
            $this->updateItemPath($item, $newRelativePath);

            $updatedCount++;
            $io->text(sprintf('✓ Item #%d (%s): %s -> %s', $itemId, $itemName, $filenameOnly, $newRelativePath));
        }

        // Bewaar alle DB-wijzigingen
        $this->em->flush();

        // Extra check: Probeer de hoofd-bronmap cdn/images/V3__Items ook op te ruimen als die helemaal leeg is
        $this->removeDirectoryIfEmpty($sourceBaseDir);

        $io->newLine();
        $io->success(
            sprintf(
                'Klaar! Database & Bestanden gesynchroniseerd voor %d items. (%d niet gevonden, %d reeds correct/overgeslagen)',
                $updatedCount,
                $notFoundCount,
                $skippedCount
            )
        );

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

    /**
     * Zoekt een bestand in een map.
     * Als $filename is opgegeven, zoekt hij ook case-insensitive en flexibel in submappen.
     */
    private function findFileInDir(string $dir, ?string $filename = null): ?string
    {
        if (!is_dir($dir)) {
            return null;
        }

        $finder = new Finder();

        if ($filename) {
            // Zoek case-insensitive naar de bestandsnaam in $dir (inclusief submappen)
            $finder->files()->in($dir)->name('/^' . preg_quote($filename, '/') . '$/i');
        } else {
            // Pak het eerste bestand in de specifieke map
            $finder->files()->in($dir)->depth('== 0');
        }

        foreach ($finder as $file) {
            return $file->getRealPath();
        }

        return null;
    }

    /**
     * Verwijdert een directory als deze helemaal leeg is (geen bestanden/submappen meer bevat).
     */
    private function removeDirectoryIfEmpty(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $finder = new Finder();
        $finder->in($dir);

        // Als er geen bestanden of submappen meer in staan, verwijder de map
        if (!$finder->hasResults()) {
            $this->filesystem->remove($dir);
        }
    }
}
