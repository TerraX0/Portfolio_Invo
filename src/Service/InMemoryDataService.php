<?php
// src/Service/InMemoryDataService.php
namespace App\Service;

class InMemoryDataService
{
    public function findAllZielfahndungen(): array
    {
        return [
            [
                'id' => 1, 
                'vorgangsnummer' => 'ZF-2025-001', 
                'name' => 'Mustermann, Max'
            ],
            [
                'id' => 2, 
                'vorgangsnummer' => 'ZF-2025-002', 
                'name' => 'Schmidt, Anna'
            ],
        ];
    }
    
    public function findAllFahndungen(): array
    {
        return [
            [
                'id' => 1, 
                'vorgangsnummer' => 'FA-2025-001', 
                'name' => 'Müller',
                'vorname' => 'Peter',
                'gebdatum' => '1985-03-15',
                'loeschung' => '2026-12-31',
                'erfassung' => '2024-01-10'
            ],
            [
                'id' => 2, 
                'vorgangsnummer' => 'FA-2025-002', 
                'name' => 'Weber',
                'vorname' => 'Lisa',
                'gebdatum' => '1990-07-22',
                'loeschung' => '2027-06-30',
                'erfassung' => '2024-02-15'
            ],
            [
                'id' => 3, 
                'vorgangsnummer' => 'FA-2025-003', 
                'name' => 'Becker',
                'vorname' => 'Tom',
                'gebdatum' => '1978-11-08',
                'loeschung' => '2026-03-31',
                'erfassung' => '2023-12-05'
            ],
        ];
    }
    
    public function findFahndung(int $id): ?array
    {
        foreach ($this->findAllFahndungen() as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }
    
    public function find(int $id): ?array
    {
        // Suche zuerst in Zielfahndungen
        foreach ($this->findAllZielfahndungen() as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }
        
        // Dann in Fahndungen
        return $this->findFahndung($id);
    }
}