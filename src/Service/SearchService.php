<?php

// src/Service/SearchService.php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class SearchService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function performSearch(string $repositoryClass, array $criteria): array
    {
        // Sicherstellen, dass das Repository existiert
        $repository = $this->entityManager->getRepository($repositoryClass);

        if (!method_exists($repository, 'findByCriteria')) {
            throw new \RuntimeException("Die Methode existiert nicht.");
        }

        return $repository->findByCriteria($criteria);
    }

}