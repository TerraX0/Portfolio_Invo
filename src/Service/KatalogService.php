<?php
// src/Service/KatalogService.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class KatalogService
{
    private array $repositories;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
       {
        // Speichern des EntityManagers
        $this->em = $em;
    }

    /**
     * Gibt alle Entitäten eines bestimmten Repositorys zurück
     */
    public function getAllEntries(string $entityClass): array
    {
        // Repository über die Entität abrufen
        $repository = $this->em->getRepository($entityClass);
        return $repository->findAll();
    }

}
