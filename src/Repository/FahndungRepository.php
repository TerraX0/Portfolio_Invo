<?php

namespace App\Repository;

use App\Entity\Fahndung;
use App\Service\CriteriaFilterService;
use App\Service\QueryBuilderService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class FahndungRepository extends AbstractSearchRepository
{
    private CriteriaFilterService $criteriaFilterService;
    private QueryBuilderService $queryBuilderService;

    public function __construct(ManagerRegistry $registry, CriteriaFilterService $criteriaFilterService,
                                QueryBuilderService $queryBuilderService)
    {
        parent::__construct($registry, Fahndung::class);
        $this->criteriaFilterService = $criteriaFilterService;
        $this->queryBuilderService = $queryBuilderService;
    }

    /**
     * Find Fahndung entities by criteria.
     *
     * @param array $criteria Array von Suchkriterien.
     * @return array Array von Fahndung-Entitäten.
     * @throws ORMException
     */
    public function findByCriteria(array $criteria): array
    {
        $queryBuilder = $this->queryBuilderService->getQueryBuilder();
        $queryBuilder->select('f')->from(Fahndung::class, 'f');

        $classMetadata = $this->getEntityManager()->getClassMetadata(Fahndung::class);

        $this->criteriaFilterService->applyCriteria($queryBuilder, $criteria, 'f', $classMetadata);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Generate the next Vorgangsnummer for Fahndung.
     *
     * @return string
     * @throws ORMException
     */
    public function generateVorgangsnummer(): string
    {
        $year = date('Y');

        $lastEntry = $this->createQueryBuilder('f')
            ->select('f.vorgangsnummer AS vorgangsnummer')
            ->where('f.vorgangsnummer LIKE :year')
            ->setParameter('year', "$year-%")
            ->orderBy('f.vorgangsnummer', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if ($lastEntry !== null && isset($lastEntry['vorgangsnummer'])) {
            $lastNumber = (int)substr($lastEntry['vorgangsnummer'], strlen($year) + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // Generiere die neue Vorgangsnummer
        $vorgangsnummer = sprintf('%s-%06d', $year, $nextNumber);

        // Fallback auf "Unbekannt", wenn keine gültige Vorgangsnummer generiert wurde
        if ($vorgangsnummer === '') {
            return 'Unbekannt';
        }

        return $vorgangsnummer;
    }


    public function findByField(array $criteria): array
    {
        $queryBuilder = $this->buildSearchQuery($criteria, 'f')
            ->orderBy('f.vorgangsnummer', 'DESC');  // Sortiere nach Vorgangsnummer absteigend

        return $queryBuilder->getQuery()->getResult();
    }


}
