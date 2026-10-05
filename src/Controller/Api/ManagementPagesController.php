<?php

namespace App\Controller\Api;

use App\Entity\ManagementCategories;
use App\Entity\ManagementPages;
use App\Entity\ManagementPagesCategories;
use App\Entity\ManagementPageTypes;
use App\Entity\ManagementUsers;
use App\Entity\V3Items;
use App\Repository\ManagementCategoriesRepository;
use App\Repository\ManagementPagesCategoriesRepository;
use App\Repository\ManagementPagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/api/management-pages')]
class ManagementPagesController extends AbstractController
{
    private const SERIALIZATION_CONTEXT = [
        'groups' => ['page:read']
    ];

    #[Route('', methods: ['GET'])]
    public function index(ManagementPagesRepository $repository): JsonResponse
    {
        return $this->json(
            $repository->findAll(),
            Response::HTTP_OK,
            [],
            self::SERIALIZATION_CONTEXT
        );
    }
    #[Route('/resolve', name: 'api_pages_resolve', methods: ['GET'])]
    public function resolve(Request $request, ManagementPagesRepository $pageRepository): JsonResponse
    {
        $rawPath = $request->query->get('path', 'Home');

        // 1. Opschonen van het pad
        $path = trim($rawPath, '/');
        if (str_ends_with(strtolower($path), '.html')) {
            $path = substr($path, 0, -5);
        }

        if ($path === '' || strcasecmp($path, 'Home') === 0) {
            $path = 'Home';
        }

        // 2. Zoek via QueryBuilder (case-insensitive voor zowel 'link' als 'slug')
        $page = $pageRepository->createQueryBuilder('p')
            ->where('LOWER(p.link) = LOWER(:path)')
            ->orWhere('LOWER(p.slug) = LOWER(:path)')
            ->setParameter('path', $path)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$page) {
            return $this->json(
                [
                'error' => 'Pagina niet gevonden',
                'searched_path' => $path,
                'raw_received' => $rawPath
                ], Response::HTTP_NOT_FOUND
            );
        }

        return $this->json(
            [
            'title' => $page->getName(),
            'blocks' => $page->getBlocks(),
            ], Response::HTTP_OK, [], self::SERIALIZATION_CONTEXT
        );
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(?ManagementPages $entity): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Pagina niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $entity,
            Response::HTTP_OK,
            [],
            ['groups' => ['page:details']]
        );
    }

    /**
     * Endpoint om alle gekoppelde categorieën/tags van een pagina op te halen voor de frontend.
     */
    #[Route('/{id}/categories', methods: ['GET'])]
    public function getCategories(
        ?ManagementPages $entity,
        ManagementPagesCategoriesRepository $pageCatRepo
    ): JsonResponse {
        if (!$entity) {
            return $this->json(['error' => 'Pagina niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $relations = $pageCatRepo->findBy(['pcPage' => $entity]);

        $categories = array_map(
            function (ManagementPagesCategories $relation) {
                $cat = $relation->getPcCategory();
                return [
                'id' => $cat->getCategoryId(),
                'name' => $cat->getCategoryName(),
                ];
            }, $relations
        );

        return $this->json($categories, Response::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->toArray();
        $entity = new ManagementPages();

        $this->mapDataToEntity($data, $entity, $em);

        $em->persist($entity);
        $em->flush();

        return $this->json(
            $entity,
            Response::HTTP_CREATED,
            [],
            self::SERIALIZATION_CONTEXT
        );
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, ?ManagementPages $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Pagina niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->toArray();
        $this->mapDataToEntity($data, $entity, $em);

        $em->flush();

        return $this->json(
            $entity,
            Response::HTTP_OK,
            [],
            self::SERIALIZATION_CONTEXT
        );
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(?ManagementPages $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Pagina niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($entity);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/reorder', name: 'reorder', methods: ['PATCH', 'POST'])]
    public function reorder(
        Request $request,
        ManagementPagesRepository $pageRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $parentId = $data['parentId'] ?? null;
        $orderedIds = $data['orderedIds'] ?? [];

        if (empty($orderedIds)) {
            return new JsonResponse(['error' => 'Geen IDs meegegeven.'], Response::HTTP_BAD_REQUEST);
        }

        $parentPage = $parentId ? $pageRepository->find($parentId) : null;

        if ($parentId !== null && !$parentPage) {
            return new JsonResponse(['error' => 'Opgegeven parent pagina bestaat niet.'], Response::HTTP_NOT_FOUND);
        }

        $targetPages = $pageRepository->findBy(['id' => $orderedIds]);

        $pagesById = [];
        foreach ($targetPages as $page) {
            $pagesById[$page->getId()] = $page;
        }

        $step = 1000.0;
        $currentOrder = 1000.0;

        foreach ($orderedIds as $id) {
            if (isset($pagesById[$id])) {
                $page = $pagesById[$id];

                $page->setOrder(number_format($currentOrder, 4, '.', ''));

                if ($page->getParent() !== $parentPage) {
                    $page->setParent($parentPage);
                }

                $currentOrder += $step;
            }
        }

        $em->flush();

        return new JsonResponse(
            [
                'status' => 'success',
                'message' => 'Volgorde succesvol bijgewerkt.'
            ]
        );
    }

    /**
     * Hulpfunctie om JSON-payload toe te wijzen aan de ManagementPages Entity.
     */
    private function mapDataToEntity(array $data, ManagementPages $entity, EntityManagerInterface $em): void
    {
        if (array_key_exists('name', $data)) {
            $entity->setName($data['name']);
        }
        if (array_key_exists('slug', $data)) {
            $entity->setSlug($data['slug']);
        }
        if (array_key_exists('link', $data)) {
            $entity->setLink($data['link']);
        }
        if (array_key_exists('active', $data)) {
            $entity->setActive($data['active'] !== null ? (int)$data['active'] : null);
        }

        $statusId = $data['statusId'] ?? ($data['status'] ?? null);
        if ($statusId !== null) {
            $codeRef = $em->getRepository(\App\Entity\Code::class)->find($statusId);
            if ($codeRef) {
                $entity->setStatus($codeRef);
            }
        } else {
            $entity->setStatus(null);
        }

        if (array_key_exists('order', $data)) {
            $entity->setOrder($data['order'] !== null ? (string)$data['order'] : '0.0000');
        }
        if (array_key_exists('ignoreApi', $data)) {
            $entity->setIgnoreApi((bool)$data['ignoreApi']);
        }

        // SEO Fields
        if (array_key_exists('metaTitle', $data)) {
            $entity->setMetaTitle($data['metaTitle']);
        }
        if (array_key_exists('metaDescription', $data)) {
            $entity->setMetaDescription($data['metaDescription']);
        }
        if (array_key_exists('ogImage', $data)) {
            $entity->setOgImage($data['ogImage']);
        }
        if (array_key_exists('blocks', $data)) {
            $entity->setBlocks($data['blocks']);
        }

        // Relatie: Parent Page
        if (array_key_exists('parentId', $data)) {
            $parent = $data['parentId'] ? $em->getRepository(ManagementPages::class)->find($data['parentId']) : null;
            $entity->setParent($parent);
        }

        // Relatie: Page Type
        if (array_key_exists('typeId', $data)) {
            $type = $data['typeId'] ? $em->getRepository(ManagementPageTypes::class)->find($data['typeId']) : null;
            $entity->setType($type);
        }

        // Relatie: API Item
        $apiItemId = $data['apiItemId'] ?? $data['apiItem'] ?? null;

        // Als apiItem een object/array is vanuit de frontend, haal daar de id/itemid uit
        if (is_array($apiItemId)) {
            $apiItemId = $apiItemId['itemid'] ?? $apiItemId['id'] ?? null;
        }

        if ($apiItemId !== null || array_key_exists('apiItem', $data) || array_key_exists('apiItemId', $data)) {
            // Als ignoreApi op true staat, ontkoppelen we het item altijd
            if (!empty($data['ignoreApi'])) {
                $entity->setApiItem(null);
            } else {
                $apiItem = $apiItemId ? $em->getRepository(V3Items::class)->find((int) $apiItemId) : null;
                $entity->setApiItem($apiItem);
            }
        }

        // Relatie: User audit trail
        $currentUser = null;
        if (array_key_exists('createdByUserId', $data) && $data['createdByUserId']) {
            $user = $em->getRepository(ManagementUsers::class)->find($data['createdByUserId']);
            if ($user) {
                $entity->setCreatedBy($user);
                $currentUser = $user;
            }
        }
        if (array_key_exists('updatedByUserId', $data) && $data['updatedByUserId']) {
            $user = $em->getRepository(ManagementUsers::class)->find($data['updatedByUserId']);
            if ($user) {
                $entity->setUpdatedBy($user);
                $currentUser = $user;
            }
        }

        // Synchronisatie van Categorieën / Tags via ManagementPagesCategories
        if (array_key_exists('categoryIds', $data) && is_array($data['categoryIds'])) {
            $pageCatRepo = $em->getRepository(ManagementPagesCategories::class);
            $catRepo = $em->getRepository(ManagementCategories::class);

            $existingRelations = $pageCatRepo->findBy(['pcPage' => $entity]);

            $existingMap = [];
            foreach ($existingRelations as $relation) {
                $existingMap[$relation->getPcCategory()->getCategoryId()] = $relation;
            }

            $targetCategoryIds = array_map('intval', $data['categoryIds']);
            $now = new \DateTime();

            // Verwijder ontdane categorieën
            foreach ($existingMap as $catId => $relation) {
                if (!in_array($catId, $targetCategoryIds, true)) {
                    $em->remove($relation);
                }
            }

            // Voeg nieuwe categorieën toe
            foreach ($targetCategoryIds as $catId) {
                if (!isset($existingMap[$catId])) {
                    $category = $catRepo->find($catId);
                    if ($category) {
                        $newRelation = new ManagementPagesCategories();
                        $newRelation->setPcPage($entity);
                        $newRelation->setPcCategory($category);
                        $newRelation->setPcCreatedAt($now);
                        $newRelation->setPcLastModifiedAt($now);

                        if ($currentUser) {
                            $newRelation->setPcOwner($currentUser);
                            $newRelation->setPcLastModifier($currentUser);
                        }

                        $em->persist($newRelation);
                    }
                }
            }
        }
    }

    #[Route('/{id}/upload', methods: ['POST'])]
    public function uploadImage(
        ?ManagementPages $entity,
        Request $request,
        SluggerInterface $slugger
    ): JsonResponse {
        if (!$entity) {
            return $this->json(['error' => 'Pagina niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $cdnMountPath = $this->getParameter('cdn_mount_path');

        // 1. Haal het geüploade bestand op uit het Request (FormData)
        $file = $request->files->get('file') ?? $request->files->get('image');

        if (!$file) {
            return $this->json(['error' => 'Geen bestand ontvangen.'], Response::HTTP_BAD_REQUEST);
        }

        // 2. Mapstructuur: cdn_mount_path/pages/{pageId}/
        $targetDir = rtrim($cdnMountPath, '/') . '/pages/' . $entity->getId();

        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }

        // 3. Bestandsnaam opschonen en uniek maken
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename)->lower();
        $newFilename = sprintf('%s-%s.%s', $safeFilename, uniqid(), $file->guessExtension() ?? 'jpg');

        try {
            // 4. Bestand verplaatsen naar CDN
            $file->move($targetDir, $newFilename);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Bestand kon niet worden opgeslagen: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // 5. Relatieve URL teruggeven voor Vue
        $relativePath = 'pages/' . $entity->getId() . '/' . $newFilename;

        return $this->json(
            [
            'success' => true,
            'url' => $relativePath,
            'filename' => $newFilename
            ], Response::HTTP_OK
        );
    }




}
