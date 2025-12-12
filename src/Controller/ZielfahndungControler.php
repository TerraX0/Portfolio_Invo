<?php
// src/Controller/ZielfahndungController.php

namespace App\Controller;

use App\Form\ZielfahndungSearchType;
use App\Service\KriteriumService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ZielfahndungType;
use App\Service\InMemoryDataService;

class ZielfahndungController extends AbstractController
{
    private InMemoryDataService $dataService;
    private KriteriumService $kriteriumService;

    public function __construct(
        InMemoryDataService $dataService,
        KriteriumService $kriteriumService
    ) {
        $this->dataService = $dataService;
        $this->kriteriumService = $kriteriumService;
    }

    #[Route('/zielfahndung/new', name: 'zielfahndung')]
    public function new(Request $request): Response
    {
        // Bei InMemory-Daten können wir keine neuen Einträge erstellen
        $this->addFlash('info', 'Im Demo-Modus können keine neuen Einträge erstellt werden.');
        return $this->redirectToRoute('zielfahndung_search');
    }

    #[Route('/zielfahndung/{id<\d+>}', name: 'zielfahndung_show')]
    public function show(Request $request, int $id): Response
    {
        // Daten aus InMemoryDataService laden (als Array)
        $zielfahndungData = $this->dataService->find($id);

        if (!$zielfahndungData) {
            $this->addFlash('error', 'Zielfahndung mit der ID ' . $id . ' wurde nicht gefunden.');
            return $this->redirectToRoute('zielfahndung_search');
        }

        // Anzeige als reines Array (ohne Formular, da wir keine Entities haben)
        return $this->render('zielfahndung/show.html.twig', [
            'title' => "Zielfahndung - Datensatz ansehen",
            'zielfahndung' => $zielfahndungData,
        ]);
    }

    #[Route('/zielfahndung/search', name: 'zielfahndung_search')]
    public function search(Request $request): Response
    {
        $form = $this->createForm(ZielfahndungSearchType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('zielfahndung/search.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $formData = $form->getData();

        if (!empty($formData['vorgangsnummer']) && strlen($formData['vorgangsnummer']) < 11) {
            $this->addFlash('error', 'Die Vorgangsnummer muss mindestens 11 Zeichen lang sein.');
            return $this->redirectToRoute('zielfahndung_search');
        }

        $page = $request->query->getInt('page', 1);
        $resultsPerPage = 15;

        // Alle Zielfahndungen aus dem InMemory-Service holen
        $allResults = $this->dataService->findAllZielfahndungen();

        // Filtern basierend auf Suchkriterien
        $filteredResults = array_filter($allResults, function($item) use ($formData) {
            // Vorgangsnummer-Filter
            if (!empty($formData['vorgangsnummer'])) {
                if (stripos($item['vorgangsnummer'], $formData['vorgangsnummer']) === false) {
                    return false;
                }
            }
            
            // Name-Filter
            if (!empty($formData['name'])) {
                if (stripos($item['name'], $formData['name']) === false) {
                    return false;
                }
            }
            
            // Weitere Filter können hier hinzugefügt werden
            
            return true;
        });

        if (empty($filteredResults)) {
            $this->addFlash('error', 'Keine Ergebnisse gefunden.');
            return $this->redirectToRoute('zielfahndung_search');
        }

        // Manuelle Paginierung
        $totalResults = count($filteredResults);
        $totalPages = (int) ceil($totalResults / $resultsPerPage);
        $offset = ($page - 1) * $resultsPerPage;
        $results = array_slice($filteredResults, $offset, $resultsPerPage);

        return $this->render('zielfahndung/search_results.html.twig', [
            'zielfahndungen' => $results,
            'deleteRoute' => 'zielfahndung_delete',
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/zielfahndung/{id}/delete', name: 'zielfahndung_delete')]
    public function delete(int $id): Response
    {
        // Bei InMemory-Daten können wir nichts löschen
        $this->addFlash('info', 'Im Demo-Modus können keine Einträge gelöscht werden.');
        return $this->redirectToRoute('zielfahndung_search');
    }
}