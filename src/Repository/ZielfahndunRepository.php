<?php
// src/Repository/ZielfahndungRepository.php
namespace App\Repository;

use App\Entity\Zielfahndung;
use App\Service\CriteriaFilterService;
use App\Service\QueryBuilderService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AbstractSearchRepository;

// Definiert das Repository für die Zielfahndung-Entität
class ZielfahndungRepository extends AbstractSearchRepository
{
    // Deklariert private Eigenschaften für die beiden Services
    private CriteriaFilterService $criteriaFilterService;
    private QueryBuilderService $queryBuilderService;

    // Konstruktor nimmt den ManagerRegistry und die beiden Services entgegen
    public function __construct(ManagerRegistry $registry, CriteriaFilterService $criteriaFilterService,
                                QueryBuilderService $queryBuilderService)
    {   // Ruft den Konstruktor der übergeordneten Klasse auf, um die EntityManager zu initialisieren
        parent::__construct($registry, Zielfahndung::class);
        // Weist die übergebenen Services den privaten Eigenschaften zu
        $this->criteriaFilterService = $criteriaFilterService;
        $this->queryBuilderService = $queryBuilderService;
    }

    /**
     * Find Zielfahndung entities by criteria.
     *
     * @param array $criteria // Parameter: Array von Suchkriterien
     * @return array // Gibt ein Array von Zielfahndung-Entitäten zurück
     * @throws ORMException
     */
    //TODO: evtl Felder "sachbearbeiter1" und "sachbearbeiter2" zusammen durschsuchen und anzeigen (wie im alten Invo)
    public function findByCriteria(array $criteria): array
    {
        // Verwende die Methode getQueryBuilder() aus dem QueryBuilderService, um einen leeren QueryBuilder zu erstellen
        $queryBuilder = $this->queryBuilderService->getQueryBuilder();

        // Füge die notwendigen Abfragen und Filter hinzu
        $queryBuilder->select('z')->from(Zielfahndung::class, 'z');

        // Hole die Metadaten der Entität
        $classMetadata = $this->entityManager->getClassMetadata(Zielfahndung::class);

        // Wende die Filterkriterien an
        $this->criteriaFilterService->applyCriteria($queryBuilder, $criteria, 'z', $classMetadata);

        // Führe die Abfrage aus und gib das Ergebnis zurück
        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Generate the next Vorgangsnummer for Zielfahndung.
     *
     * @return string
     *
     * @throws ORMException
     */
    public function generateVorgangsnummer(): string
    {
        $year = date('Y'); // Aktuelles Jahr

        // Letzte Vorgangsnummer aus der Datenbank abrufen
        $lastEntry = $this->createQueryBuilder('z')
            ->select('z.vorgangsnummer AS vorgangsnummer')
            ->where('z.vorgangsnummer LIKE :year')
            ->setParameter('year', "$year-%")
            ->orderBy('z.vorgangsnummer', 'DESC')
            //nur ein einziges Ergebnis aus der Datenbankabfrage zurückgegeben wird.
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        // Wenn kein Eintrag mit dem aktuellen Jahr gefunden wird
        if ($lastEntry !== null) {
            $lastNumber = (int)substr($lastEntry['vorgangsnummer'], strlen($year) + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1; // Wenn keine Einträge gefunden wurden, starte bei 1
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
        //dump($criteria);
        $queryBuilder = $this->buildSearchQuery($criteria, 'z');
        $queryBuilder->orderBy('z.vorgangsnummer', 'DESC');

       /* //Debugguing
        // SQL abfragen
        dump($queryBuilder->getQuery()->getSQL());

        // DQL abfragen
        dump($queryBuilder->getQuery()->getDQL());
       */

        return $queryBuilder->getQuery()->getResult(); // Hier wird das Ergebnis abgerufen

    }


}