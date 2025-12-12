<?php

namespace App\Utils;

class OperatorHelper
{
    /**
     * Map operator to Doctrine query language.
     *
     * @param string|null $operator
     * @param mixed $value
     * @param string $fieldname
     * @return string
     */
    public static function mapOperator(?string $operator, mixed $value, string $fieldname): string
    {

    /*    // Für Assoziationen: Wenn das Feldname mit '_id' endet, setze den Operator explizit auf '='
        if (str_ends_with($fieldname, '_id')) {
            return '=';
        } */

        // Wenn kein Operator übergeben wurde, setze den Standardoperator '='
        $operator = $operator ?? '=';

        // Wenn der Wert ein DateTime-Objekt ist, wandeln wir es in einen String um
        if ($value instanceof \DateTimeInterface) {
            $value = $value->format('Y-m-d');
        }

        // Operator für LIKE
        if ($operator === 'LIKE') {
            return "LIKE :$fieldname";
        }

        // Falls der Operator 'range' ist, behandeln wir es als BETWEEN
        if ($operator === 'range') {
            if (is_array($value) && count($value) === 2) {
                return "BETWEEN :value_start AND :value_end";
            }
            return ''; // Fehlerfall
        }

        // Operatoren für IN, NOT IN
        if ($operator === 'IN' || $operator === 'NOT IN') {
            if (is_array($value) && count($value) > 0) {
                // Erstelle Platzhalter für die Werte im Array
                $placeholders = array_map(function ($index) use ($fieldname) {
                    return ":{$fieldname}_{$index}";
                }, array_keys($value));

                return "$operator (" . implode(', ', $placeholders) . ")";
            }
            return ''; // Fehlerfall für ungültige Werte
        }

        if ($operator === 'IS NULL' || $operator === 'IS NOT NULL') {
            return "$operator";
        }

        // Standardoperatoren
        return match ($operator) {
            'less_than' => "< :$fieldname",
            'greater_than' => "> :$fieldname",
            'less_than_or_equal' => "<= :$fieldname",
            'greater_than_or_equal' => ">= :$fieldname",
            default => "= :$fieldname",
        };
    }
}
