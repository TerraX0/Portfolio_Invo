<?php

namespace App\Service;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;

class QueryBuilderService
{// Deklariert eine private Variable für den EntityManager, die für die Datenbankoperationen zuständig ist
    private EntityManagerInterface $entityManager;
    // Deklariert eine private Variable für den Service, der Filter auf die Abfragen anwendet
    private CriteriaFilterService $criteriaFilterService;

    // Konstruktor, der den EntityManager und den CriteriaFilterService injiziert
    public function __construct(EntityManagerInterface $entityManager,
                                CriteriaFilterService $criteriaFilterService)
    {
        // Konstruktor, der den EntityManager und den CriteriaFilterService injiziert.
        $this->entityManager = $entityManager;
        $this->criteriaFilterService = $criteriaFilterService;
    }

    /**
     * Baut die Query basierend auf den Kriterien und den Formulardaten.
     *
     * @throws ORMException
     */
    public function getPaginatedResults(string $entityClass,
                                        string $alias,
                                        array $criteria,
                                        ?int $page = 1,
                                        ?int $resultsPerPage = 15): array
    {
        // Erstelle den QueryBuilder
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select($alias)->from($entityClass, $alias);

        // Wende die Filterkriterien an
        $this->criteriaFilterService->applyCriteria($qb, $criteria, $alias);
        // Überprüfe, ob die Parameter im array $criteria vorhanden ist
        // Setze (weise zu) die Standardwerte für die Sortierung, falls keine in der FormType gesetzt
        $orderBy = $criteria['orderBy'] ?? 'vorgangsnummer';
        $orderDirection = $criteria['orderDirection'] ?? 'DESC'; // Standardzuweisung

        // Anwendung der Sortierung
        $qb->orderBy("$alias.$orderBy", $orderDirection);

        // Paginierung anwenden
        // Der Offset gibt an, ab welchem Ergebnis die Daten geladen werden sollen.
        // Wenn die aktuelle Seite z.B. 2 ist und 10 Ergebnisse pro Seite angezeigt werden,
        // ergibt sich der Offset als (2 - 1) * 10 = 10.
        $offset = ($page - 1) * $resultsPerPage;
        // setFirstResult($offset): Definiert den Startpunkt der Ergebnisse - ab welchem Index (Offset) die Ergebnisse abgeholt werden.
        // setMaxResults($resultsPerPage): Gibt die maximale Anzahl an Ergebnissen pro Seite an, z.B. 10 Ergebnisse pro Seite
        $qb->setFirstResult($offset)->setMaxResults($resultsPerPage);

        // Berechne die Gesamtanzahl der Ergebnisse (ohne Paginierung)
        // Ein neuer QueryBuilder wird erstellt, um eine separate Abfrage für die Anzahl der Datensätze zu machen
        $qbForCount = $this->entityManager->createQueryBuilder();
        // select('COUNT(' . $alias . '.id)'): Zählt die Anzahl der Datensätze in der Entität.
        // Anstatt alle Datensätze zurückzugeben, wird nur die Anzahl der Datensätze gezählt
        $qbForCount->select('COUNT(' . $alias . '.id)')
            ->from($entityClass, $alias);
        // Wende auf den Seitenzähler die gleichen Kriterien an, wie für die Hauptabfrage:
        //Damit die Filter aus $criteria auch bei der Zählabfrage gelten (vermeidet unstimmige Seitenzählung)
        $this->criteriaFilterService->applyCriteria($qbForCount, $criteria, $alias);
        // Hole die Gesamtanzahl der Ergebnisse
        $totalCount = (int) $qbForCount->getQuery()->getSingleScalarResult();

        // Berechne die Gesamtzahl der Seiten
        $totalPages = ceil($totalCount / $resultsPerPage);

        // Debugging: SQL und Parameter debuggen
        /*  $sql = $qb->getQuery()->getSQL();
        $parameters = $qb->getQuery()->getParameters();

        // Protokolliere die SQL-Anweisung und Parameter
        dump("Generated SQL: $sql");
        dump("Parameters: " . json_encode($parameters));

        // Debugging die generierte SQL-Abfrage
        $sql = $qbForCount->getQuery()->getSQL();
        dump($sql);  // Zeigt die Abfrage vor der Ausführung */

        // Führe die Abfrage aus, um die Ergebnisse zu holen
        $results = $qb->getQuery()->getResult();

        return [
            'results' => $results,
            'totalPages' => $totalPages
        ];
    }
    
}