<?php

namespace App\Repository;

use App\Entity\IntZusammenarbeit;
use App\Service\CriteriaFilterService;
use App\Service\QueryBuilderService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class IntZusammenarbeitRepository extends AbstractSearchRepository
{
    private CriteriaFilterService $criteriaFilterService;
    private QueryBuilderService $queryBuilderService;

    public function __construct(ManagerRegistry $registry, CriteriaFilterService $criteriaFilterService,
                                QueryBuilderService $queryBuilderService)
    {
        parent::__construct($registry, IntZusammenarbeit::class);
        $this->criteriaFilterService = $criteriaFilterService;
        $this->queryBuilderService = $queryBuilderService;
    }

    /**
     * Find IntZusammenarbeit entities by criteria.
     *
     * @param array $criteria Array von Suchkriterien.
     * @return array Array von IntZusammenarbeit-Entitäten.
     */
    public function findByCriteria(array $criteria): array
    {
        $queryBuilder = $this->queryBuilderService->getQueryBuilder();
        $queryBuilder->select('i')->from(IntZusammenarbeit::class, 'i');

        $classMetadata = $this->getEntityManager()->getClassMetadata(IntZusammenarbeit::class);

        $this->criteriaFilterService->applyCriteria($queryBuilder, $criteria, 'i', $classMetadata);

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

        if ($lastEntry !== null) {
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
        $queryBuilder = $this->buildSearchQuery($criteria, 'i')
            ->orderBy('i.vorgangsnummer', 'DESC');  // Sortiere nach Vorgangsnummer absteigend
        //Debugging
        /* $sql = $queryBuilder->getQuery()->getSQL();
        dump($sql); */

        return $queryBuilder->getQuery()->getResult();
    }

}
