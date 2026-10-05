<?php

namespace App\Controller\Api;

use App\Entity\ManagementPages;
use App\Entity\ManagementPagesCategories;
use App\Entity\ManagementCategories;
use App\Repository\ManagementPagesCategoriesRepository;
use App\Repository\ManagementPagesRepository;
use App\Repository\ManagementCategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/management-pages-categories')]
class ManagementPagesCategoriesController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(ManagementPagesCategoriesRepository $repository): JsonResponse
    {
        return $this->json($repository->findAll());
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(?ManagementPagesCategories $entity): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($entity);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->toArray();
        $entity = new ManagementPagesCategories();

        if (isset($data['categoryName'])) {
            $entity->setCategoryName($data['categoryName']);
        }

        $em->persist($entity);
        $em->flush();

        return $this->json($entity, Response::HTTP_CREATED);
    }

    #[Route('/link/{pageId}', methods: ['POST'])]
    public function linkCategoryToPage(
        int $pageId,
        Request $request,
        ManagementPagesRepository $pageRepository,
        ManagementCategoriesRepository $categoryRepository,
        ManagementPagesCategoriesRepository $pageCategoryRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $page = $pageRepository->find($pageId);
        if (!$page) {
            return $this->json(['error' => 'Pagina niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->toArray();
        $categoryValue = $data['category'] ?? null;

        if (!$categoryValue) {
            return $this->json(['error' => 'Geen categorie meegegeven'], Response::HTTP_BAD_REQUEST);
        }

        $newCategory = null;

        // 1. Zoek of maak de ManagementCategories entiteit
        if (is_numeric($categoryValue)) {
            $category = $categoryRepository->find((int) $categoryValue);
            if (!$category) {
                return $this->json(['error' => 'Categorie niet gevonden'], Response::HTTP_NOT_FOUND);
            }
        } else {
            // Zoek in ManagementCategoriesRepository op $categoryName
            $category = $categoryRepository->findOneBy(['categoryName' => trim($categoryValue)]);

            if (!$category) {
                $category = new ManagementCategories();
                $category->setCategoryName(trim($categoryValue));
                $category->setCategoryCreatedAt(new \DateTime());
                $category->setCategoryLastModifiedAt(new \DateTime());

                if ($this->getUser()) {
                    $category->setCategoryOwner($this->getUser());
                    $category->setCategoryLastModifier($this->getUser());
                }

                $em->persist($category);
                $newCategory = $category;
            }
        }

        // 2. Controleer of de koppeling al bestaat in ManagementPagesCategories
        $existingLink = $pageCategoryRepository->findOneBy(
            [
            'pcPage' => $page,
            'pcCategory' => $category
            ]
        );

        if (!$existingLink) {
            $pageCategoryLink = new ManagementPagesCategories();

            // 2. Gebruik de correcte setters
            $pageCategoryLink->setPcPage($page);
            $pageCategoryLink->setPcCategory($category);
            $pageCategoryLink->setPcCreatedAt(new \DateTime());
            $pageCategoryLink->setPcLastModifiedAt(new \DateTime());

            if ($this->getUser()) {
                $pageCategoryLink->setPcOwner($this->getUser());
                $pageCategoryLink->setPcLastModifier($this->getUser());
            }

            $em->persist($pageCategoryLink);
        }

        $em->flush();

        return $this->json(
            [
            'success' => true,
            'message' => 'Categorie succesvol gekoppeld',
            'newCategory' => $newCategory ? [
            'id' => $newCategory->getCategoryId(),
            'name' => $newCategory->getCategoryName()
            ] : null
            ], Response::HTTP_OK
        );
    }

    /**
     * Ontkoppel een categorie van een pagina
     */
    #[Route('/unlink/{pageId}/{categoryId}', methods: ['DELETE', 'POST'])]
    public function unlinkCategoryFromPage(
        int $pageId,
        int $categoryId,
        ManagementPagesRepository $pageRepository,
        ManagementCategoriesRepository $categoryRepository,
        ManagementPagesCategoriesRepository $pageCategoryRepository,
        EntityManagerInterface $em
    ): JsonResponse {
        $page = $pageRepository->find($pageId);
        $category = $categoryRepository->find($categoryId);

        if (!$page || !$category) {
            return $this->json(['error' => 'Pagina of categorie niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        // Zoek het exacte koppelrecord op basis van de twee relaties
        $pageCategoryLink = $pageCategoryRepository->findOneBy(
            [
            'pcPage' => $page,
            'pcCategory' => $category
            ]
        );

        if ($pageCategoryLink) {
            // Verwijder ENKEL het koppelrecord (de categorie zelf blijft bestaan!)
            $em->remove($pageCategoryLink);
            $em->flush();
        }

        return $this->json(
            [
            'success' => true,
            'message' => 'Categorie succesvol ontkoppeld van pagina'
            ]
        );
    }

    #[Route('/{id}', methods: ['PUT', 'PATCH'])]
    public function update(Request $request, ?ManagementPagesCategories $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $data = $request->toArray();
        if (isset($data['categoryName'])) {
            $entity->setCategoryName($data['categoryName']);
        }

        $em->flush();

        return $this->json($entity);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(?ManagementPagesCategories $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($entity);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }



}
