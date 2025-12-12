<?php
namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use App\Utils\OperatorHelper;

class CriteriaFilterService
{
    // Diese Methode wendet Filterkriterien auf eine Abfrage an
    public function applyCriteria(QueryBuilder $queryBuilder, array $criteria, string $alias): void
    {
        // Iteriert über alle Kriterien (z.B. Filterbedingungen), die auf die Abfrage angewendet werden sollen
        foreach ($criteria as $kriterium) {
            // Holt den Feldnamen, Operator und Wert des aktuellen Kriteriums
            $field = $kriterium->getFieldname();
            $operator = $kriterium->getOperator();
            $value = $kriterium->getValue();

                // Verwende den OperatorHelper, um den Operator zu überprüfen und zu setzen
                $operator = OperatorHelper::mapOperator($operator, $value, $field);

                // Falls eine gültige SQL-Bedingung existiert, wird sie in die Abfrage aufgenommen
                if (!empty($operator)) {
                    // Fügt die WHERE-Bedingung für das Feld hinzu
                    $queryBuilder->andWhere("$alias.$field $operator")
                        // Setzt den Wert für den Parameter des Feldes
                        ->setParameter($field, $value);
                }

        }
    }
}
