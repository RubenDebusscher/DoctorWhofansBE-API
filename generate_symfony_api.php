<?php
// generate_symfony_api.php

$entityDir = __DIR__ . '/src/Entity';
$repoDir   = __DIR__ . '/src/Repository';
$ctrlDir   = __DIR__ . '/src/Controller/Api';

// Maak benodigde mappen aan
foreach ([$repoDir, $ctrlDir] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

$files = glob($entityDir . '/*.php');
$count = 0;

foreach ($files as $file) {
    $className = basename($file, '.php');

    // Sla eventuele niet-entiteit PHP bestanden over
    if ($className === 'index') { continue;
    }

    $repoClass = $className . 'Repository';
    $ctrlClass = $className . 'Controller';

    // ----------------------------------------------------
    // 1. REPOSITORY GENERATOR
    // ----------------------------------------------------
    $repoFile = $repoDir . '/' . $repoClass . '.php';
    $repoTemplate = <<<PHP
<?php

namespace App\Repository;

use App\Entity\\{$className};
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<{$className}>
 */
class {$repoClass} extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry \$registry)
    {
        parent::__construct(\$registry, {$className}::class);
    }
}
PHP;
    file_put_contents($repoFile, $repoTemplate);

    // ----------------------------------------------------
    // 2. SYMFONY API CONTROLLER GENERATOR
    // ----------------------------------------------------
    // Kebab-case van de klassenaam maken voor nette URL routes (bijv. PageType -> page-type)
    $routePath = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $className));

    $ctrlFile = $ctrlDir . '/' . $ctrlClass . '.php';
    $ctrlTemplate = <<<PHP
<?php

namespace App\Controller\Api;

use App\Entity\\{$className};
use App\Repository\\{$repoClass};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/{$routePath}s')]
class {$ctrlClass} extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index({$repoClass} \$repository): JsonResponse
    {
        return \$this->json(\$repository->findAll());
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(?{$className} \$entity): JsonResponse
    {
        if (!\$entity) {
            return \$this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        return \$this->json(\$entity);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request \$request, EntityManagerInterface \$em): JsonResponse
    {
        \$data = \$request->toArray();
        \$entity = new {$className}();

        // TODO: Map hier de velden van \$data naar je entiteit setter methodes
        // Bijvoorbeeld: \$entity->setName(\$data['name'] ?? null);

        \$em->persist(\$entity);
        \$em->flush();

        return \$this->json(\$entity, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Request \$request, ?{$className} \$entity, EntityManagerInterface \$em): JsonResponse
    {
        if (!\$entity) {
            return \$this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        \$data = \$request->toArray();

        // TODO: Update hier de velden van je entiteit via setters

        \$em->flush();

        return \$this->json(\$entity);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(?{$className} \$entity, EntityManagerInterface \$em): JsonResponse
    {
        if (!\$entity) {
            return \$this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        \$em->remove(\$entity);
        \$em->flush();

        return \$this->json(null, Response::HTTP_NO_CONTENT);
    }
}
PHP;
    file_put_contents($ctrlFile, $ctrlTemplate);

    echo "✅ Gegenereerd: {$repoClass} & {$ctrlClass}\n";
    $count++;
}

echo "\n🚀 Voltooid! Er zijn {$count} Repositories en Controllers gegenereerd.\n";
