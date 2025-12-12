<?php
namespace App\Service;

use App\Entity\Fahndung;
use App\Entity\IntZusammenarbeit;
use App\Entity\Zielfahndung;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use App\Classes\Kriterium;
use App\Classes\Type;

class KriteriumService
{
    private EntityManagerInterface $entityManager; // EntityManager zum Zugriff auf Doctrine
    private array $entityMetadata; // Array zur Speicherung der Metadaten der Entities

    public function __construct(EntityManagerInterface $entityManager)
    {
        // Initialisiert den EntityManager, der für den Zugriff auf die Datenbank und Entitätsmetadaten zuständig ist
        $this->entityManager = $entityManager;

        // Speichert die Metadaten für verschiedene Entitätsklassen in einem Array
        $this->entityMetadata = [
            Zielfahndung::class => $this->entityManager->getClassMetadata(Zielfahndung::class),
            IntZusammenarbeit::class => $this->entityManager->getClassMetadata(IntZusammenarbeit::class),
            Fahndung::class => $this->entityManager->getClassMetadata(Fahndung::class),
        ];
    }

    public function createKriterien(array $formData, string $entityClass): array
    {
        $criteria = []; // Array zum Speichern der erstellten Kriterium-Objekte

        // Überprüft, ob die Entitätsklasse in den Metadaten vorhanden ist
        if (!isset($this->entityMetadata[$entityClass])) {
            throw new \InvalidArgumentException("Unbekannte Entitätsklasse: $entityClass");
        }

        // Durchläuft die Formulardaten und erstellt für jedes Feld ein Kriterium
        //$key ist der Name des Feldes im Formular, das vom Benutzer ausgefüllt wird, und wird durch das Durchlaufen des Arrays $formData (Controller) erhalten
        foreach ($formData as $key => $value) {
            // Überspringt die Felder 'orderBy' und 'orderDirection', die keine Filterkriterien sind
            if ($key === 'orderBy' || $key === 'orderDirection') {
                continue;
            }

            // Überspringt Felder, die null sind oder die auf ein Operator-Feld hinweisen
            if ($value === null || str_ends_with($key, 'Operator')) {
                continue;
            }

            // Der Operator wird aus den Formulardaten extrahiert (Standard ist '=')
            $operatorKey = $key . 'Operator';
            $operator = $formData[$operatorKey] ?? '='; // Wenn kein Operator angegeben ist, wird '=' verwendet

            // Bestimmt die Metadaten der Entität
            $metadata = $this->entityMetadata[$entityClass];

            // Bestimmt den Typ des Feldes aus den Entitäts-Metadaten
            $type = $this->getFieldTypeFromMetadata($metadata, $key);

            // Wandelt den Datenbanktyp in den Type-Enum um
            $type = Type::from($type); // Umwandlung des Typs in das Type-Enum

            //Initialisierung der Association braucht man hier nicht
            //$association = null; // Initialisierung der Assoziation als null
            $columnName = $key; // Der Name der Datenbankspalte entspricht zunächst dem Schlüssel des Formulars
            //dump($value);
                // Wenn der Wert ein Objekt(evtl. mit Association) ist und eine 'getId'-Methode hat, wird der ID-Wert verwendet
                if (is_object($value) && method_exists($value, 'getId')) {
                    $value = $value->getId();     // Ruft die 'getId'-Methode auf und speichert das Ergebnis in der Variable $value
                    // Erstellt ein neues Kriterium mit den ermittelten Werten und fügt es dem Array hinzu
                    $criteria[] = new Kriterium($columnName, $value, "=", Type::INTEGER);
                    continue;
                }

            // Für Teilstringsuche im Text: Wenn der Typ STRING ist und der Wert nicht leer ist, wird der Operator auf LIKE gesetzt
            if ($type === Type::STRING && !empty($value)) {
                $operator = 'LIKE'; // Setzt den Operator auf LIKE für Textsuche
                $value = "%" . $value . "%"; // Fügt Wildcards hinzu, um eine Teilstring-Suche zu ermöglichen
            }

            // Erstellt ein neues Kriterium-Objekt mit den ermittelten Werten und fügt es dem Array hinzu
            $criteria[] = new Kriterium($columnName, $value, $operator, $type);
        }
        return $criteria; // Gibt das Array mit den Kriterium-Objekten zurück
    }

    private function getFieldTypeFromMetadata(ClassMetadata $metadata, string $field): string
    {
        // Prüft, ob das Feld in den Entitäts-Metadaten als normales Datenbankfeld existiert
        if ($metadata->hasField($field)) {
            $type = $metadata->getTypeOfField($field);  // Holt den Typ des Feldes aus den Metadaten
            // Wenn der Typ 'text' ist, behandeln wir ihn als STRING
            if ($type === 'text') {
                //dd(Type::STRING->value);
                return Type::STRING->value; // Gibt den 'string'-Wert des Enums zurück
            }
            return $type; // Gibt den tatsächlichen Typ des Feldes zurück
        }

        // Wenn kein Typ gefunden wurde, wird standardmäßig 'string' zurückgegeben
        return Type::STRING->value; // Rückgabe des 'string'-Werts des Enums
    }
}