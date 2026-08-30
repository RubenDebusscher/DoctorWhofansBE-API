<?php
namespace App\Controller\Api;

use App\Entity\Code;
use App\Entity\CodeGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/v1/lookup/codes', name: 'api_v1_codes_')]
class CodeController extends AbstractController
{
    /**
     * Publieke GET: Vervangt de uitzondering uit GenericApiController.
     * Hoge priority (20) zorgt dat deze voorrang krijgt op /lookup/{entitySlug}.
     */
    #[Route('', name: 'list', methods: ['GET'], priority: 20)]
    public function list(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $qb = $em->getRepository(Code::class)->createQueryBuilder('e');

        // 1. Check of alle statusen gevraagd worden (bijv. via ?includeInactive=true of ?all=1)
        $includeInactive = $request->query->getBoolean('includeInactive', false)
                    || $request->query->getBoolean('all', false);

        if (!$includeInactive) {
            // Publieke modus: Filter WEL op actief & datum
            if (!$request->query->has('isActive')) {
                $qb->andWhere('e.isActive = :defaultIsActive')
                    ->setParameter('defaultIsActive', true);
            }

            $qb->andWhere('e.validUntil IS NULL OR e.validUntil > :now')
                ->setParameter('now', new \DateTime());
        }

        // Altijd sorteren op displayOrder
        $qb->addOrderBy('e.displayOrder', 'ASC');

        // 2. Relatie filtering (?codeGroup_codeKey=cast_roles OF ?group=cast_roles)
        $groupKey = $request->query->get('codeGroup_codeKey') ?? $request->query->get('group');
        if ($groupKey) {
            $qb->leftJoin('e.codeGroup', 'codeGroup')
                ->andWhere('codeGroup.codeKey = :groupKey')
                ->setParameter('groupKey', $groupKey);
        }

        // 3. Volledige selectie (inclusief label, codeValue, isActive, validUntil)
        $qb->select(
            'e.id AS id',
            'e.label AS label',
            'e.label AS text',              // Voor backwards compatibility
            'e.codeValue AS codeValue',
            'e.codeValue AS code',          // Voor backwards compatibility
            'e.shortLabel AS shortLabel',
            'e.displayOrder AS displayOrder',
            'e.isActive AS isActive',
            'e.validUntil AS validUntil',
            'e.parameters AS parameters'
        );

        $results = $qb->getQuery()->getArrayResult();

        // Formatteer validUntil naar ISO string voor JavaScript / Vue
        $formattedResults = array_map(
            function ($item) {
                if (isset($item['validUntil']) && $item['validUntil'] instanceof \DateTimeInterface) {
                    $item['validUntil'] = $item['validUntil']->format(\DateTimeInterface::ATOM);
                }
                return $item;
            }, $results
        );

        return new JsonResponse($formattedResults, Response::HTTP_OK);
    }

    /**
     * Admin POST: Maak een nieuwe code aan
     */
    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em
    ): JsonResponse {
        // Dankzij jouw security.yaml is deze route al afgeschermd met IS_AUTHENTICATED_FULLY!
        $data = json_decode($request->getContent(), true) ?? [];

        $code = new Code();
        // Deserialiseer of koppel handmatig de velden / CodeGroup
        if (!$code->getId() && isset($data['groupKey'])) {
            $group = $em->getRepository(CodeGroup::class)->findOneBy(['codeKey' => $data['groupKey']]);
            if ($group) {
                $code->setCodeGroup($group);
            }
        }

        $code->setCodeValue($data['codeValue'] ?? $data['code'] ?? '');
        $code->setLabel($data['label'] ?? $data['text'] ?? '');
        $code->setShortLabel($data['shortLabel'] ?? null);
        $code->setDisplayOrder((int)($data['displayOrder'] ?? 0));
        $code->setIsActive((bool)($data['isActive'] ?? true));

        // Datum omzetten naar \DateTime instance (indien meegegeven)
        if (!empty($data['validUntil'])) {
            $code->setValidUntil(new \DateTime($data['validUntil']));
        } else {
            $code->setValidUntil(null);
        }

        // JSON Parameters opslaan als array
        $code->setParameters($data['parameters'] ?? null);

        $em->persist($code);
        $em->flush();

        return new JsonResponse(['id' => $code->getId(), 'message' => 'Code aangemaakt'], Response::HTTP_CREATED);
    }

    /**
     * Admin PUT: Bewerk een bestaande code
     */
    #[Route('/{id}', name: 'update', methods: ['PUT', 'PATCH'])]
    public function update(
        Code $code,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $data = json_decode($request->getContent(), true) ?? [];

        // 1. CodeValue (Key)
        if (isset($data['codeValue'])) {
            $code->setCodeValue($data['codeValue']);
        } elseif (isset($data['code'])) {
            $code->setCodeValue($data['code']);
        }

        // 2. Label / Text
        if (isset($data['label'])) {
            $code->setLabel($data['label']);
        } elseif (isset($data['text'])) {
            $code->setLabel($data['text']);
        }

        // 3. ShortLabel
        if (array_key_exists('shortLabel', $data)) {
            $code->setShortLabel($data['shortLabel']);
        }

        // 4. DisplayOrder
        if (isset($data['displayOrder'])) {
            $code->setDisplayOrder((int) $data['displayOrder']);
        }

        // 5. IsActive
        if (isset($data['isActive'])) {
            $code->setIsActive((bool) $data['isActive']);
        }

        // 6. ValidUntil (DateTime conversie)
        if (array_key_exists('validUntil', $data)) {
            if (!empty($data['validUntil'])) {
                $code->setValidUntil(new \DateTime($data['validUntil']));
            } else {
                $code->setValidUntil(null);
            }
        }

        // 7. Parameters (JSON/Array)
        if (array_key_exists('parameters', $data)) {
            $code->setParameters($data['parameters']);
        }

        $em->flush();

        return new JsonResponse(['message' => 'Code bijgewerkt'], Response::HTTP_OK);
    }
}
