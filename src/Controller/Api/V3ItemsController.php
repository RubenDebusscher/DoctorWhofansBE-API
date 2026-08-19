<?php

namespace App\Controller\Api;

use App\Entity\V3Items;
use App\Repository\V3ItemsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

#[Route('/api/v3/items')]
class V3ItemsController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(V3ItemsRepository $repository): JsonResponse
    {
        return $this->json(
            $repository->findAll(),
            Response::HTTP_OK,
            [],
            [
            'groups' => ['v3_item:list'], // <--- VOEG DIT TOE (kies je eigen groepnaam)
            'circular_reference_handler' => function ($object) {
                return method_exists($object, 'getId') ? $object->getId() : null;
            }
            ]
        );
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(?V3Items $entity): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(
            $entity,
            Response::HTTP_OK,
            [],
            [
            'groups' => ['v3_item:list', 'v3_item:detail'],
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true, // Slaat alle null-velden over
            'circular_reference_handler' => function ($object) {
                if (method_exists($object, 'getItemid')) {
                    return $object->getItemid();
                }
                return method_exists($object, 'getId') ? $object->getId() : null;
            }
            ]
        );
    }

    // 3. CREATE (POST) & UPDATE (PUT)
    #[Route('', methods: ['POST'])]
    #[Route('/{id}', methods: ['PUT'])]
    public function save(
        ?V3Items $item,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        $item ??= new V3Items();

        // Deserialiseer binnenkomende JSON en gebruik ALLEEN 'v3_item:write'
        $serializer->deserialize(
            $request->getContent(),
            V3Items::class,
            'json',
            [
                'object_to_populate' => $item,
                'groups' => ['v3_item:write'] // Beschermt ID en createdBy tegen overschrijven!
            ]
        );

        // Zelf de ingelogde gebruiker koppelen (veilig!)
        if (!$item->getId()) {
            $item->setCreatedBy($this->getUser());
        }
        $item->setUpdatedBy($this->getUser());

        $em->persist($item);
        $em->flush();

        // Geef het opgeslagen object terug met de complete detail-groepen
        return $this->json(
            $item, 200, [], [
            'groups' => ['v3_item:list', 'v3_item:detail']
            ]
        );
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(?V3Items $entity, EntityManagerInterface $em): JsonResponse
    {
        if (!$entity) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($entity);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }



    #[Route('/{id}/history', methods: ['GET'])]
    public function history(int $id, EntityManagerInterface $em): JsonResponse
    {
        $repo = $em->getRepository(\App\Entity\LogEntry::class);
        $item = $em->getRepository(V3Items::class)->find($id);

        if (!$item) {
            return $this->json(['error' => 'Item niet gevonden'], Response::HTTP_NOT_FOUND);
        }

        // Haal alle log-entries voor dit specifieke item op
        $logs = $repo->getLogEntries($item);

        return $this->json($logs, Response::HTTP_OK);
    }
}





