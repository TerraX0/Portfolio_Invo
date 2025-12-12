<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractSearchRepository extends ServiceEntityRepository
{
    /**
     * Allgemeine Suchfunktion für Name, Vorname und Geburtsdatum.
     *
     * @param array $criteria  Die Suchkriterien als Array
     * @param string $alias    Das Alias des entitäten Objekts in der Query
     * @return QueryBuilder    Der QueryBuilder für die Suche
     */
    protected function buildSearchQuery(array $criteria, string $alias): QueryBuilder
    {
        // Erstellt einen neuen QueryBuilder für die angegebene Entität
        $queryBuilder = $this->createQueryBuilder($alias);

        // Wenn der Name angegeben ist, wird er in die WHERE-Klausel aufgenommen
        if (!empty($criteria['name'])) {
            // Fügt eine Bedingung hinzu, dass der Name wie der angegebene Name sein soll
            $queryBuilder->andWhere("$alias.name LIKE :name")
                // Setzt den Parameter für den Namen mit Platzhaltern
                ->setParameter('name', '%' . $criteria['name'] . '%');
        }

        // Wenn der Vorname angegeben ist, wird er in die WHERE-Klausel aufgenommen
        if (!empty($criteria['vorname'])) {
            // Fügt eine Bedingung hinzu, dass der Vorname wie der angegebene Vorname sein soll
            $queryBuilder->andWhere("$alias.vorname LIKE :vorname")
                // Setzt den Parameter für den Vornamen mit Platzhaltern
                ->setParameter('vorname', '%' . $criteria['vorname'] . '%');
        }

        // Wenn das Geburtsdatum angegeben ist, wird es in die WHERE-Klausel aufgenommen
        if (!empty($criteria['gebdatum'])) {
            // Fügt eine Bedingung hinzu, dass das Geburtsdatum exakt mit dem angegebenen übereinstimmt
            $queryBuilder->andWhere("$alias.gebdatum = :gebdatum")
                // Setzt den Parameter für das Geburtsdatum
                ->setParameter('gebdatum', $criteria['gebdatum']);
        }

        // Wenn der Inhalt angegeben ist, wird er in die WHERE-Klausel aufgenommen
        if (!empty($criteria['inhalt'])) {
            // Fügt eine Bedingung hinzu, dass der Inhalt wie der angegebene Inhalt sein soll
            $queryBuilder->andWhere("$alias.inhalt LIKE :inhalt")
                // Setzt den Parameter für den Inhalt mit Platzhaltern
                ->setParameter('inhalt', '%' . $criteria['inhalt'] . '%');
        }

        // Wenn die Vorgangsnummer im Kriterium angegeben ist, wird sie in die ORDER BY-Klausel aufgenommen
      /*  if (!empty($criteria['vorgangsnummer'])) {
            // Fügt eine ORDER BY-Klausel hinzu, um die Ergebnisse nach Vorgangsnummer zu sortieren
            $queryBuilder->orderBy("$alias.vorgangsnummer", 'DESC');  // 'ASC' für aufsteigende Reihenfolge, 'DESC' für absteigend
        }
        // Vor dem dump() den SQL ausgeben lassen
        $sql = $queryBuilder->getQuery()->getSQL();
        dump($sql);  // Zeigt die generierte SQL-Abfrage

        //dump($queryBuilder->getQuery()->getSQL()); */

        // Gibt den erstellten QueryBuilder zurück, um die Abfrage auszuführen
        return $queryBuilder;
    }
}
