<?php
namespace App\Controller;

use App\Form\FahndungSearchType;
use App\Service\KriteriumService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\FahndungType;
use App\Service\InMemoryDataService;

class FahndungController extends AbstractController
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

    #[Route('/fahndung/new', name: 'fahndung')]
    public function new(Request $request): Response
    {
        // Leeres Array für ein neues "Fahndung"-Objekt (nur zur Anzeige)
        $fahndung = [
            'id' => null,
            'vorgangsnummer' => '',
            'name' => '',
            'vorname' => '',
            'gebdatum' => null,
            'loeschung' => null,
            'erfassung' => null,
        ];

        // Formular erstellen (wird aber nicht gespeichert)
        $form = $this->createForm(FahndungType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Im Demo-Modus keine Speicherung
            $this->addFlash('info', 'Im Demo-Modus können keine neuen Einträge erstellt werden.');
            return $this->redirectToRoute('fahndung_search');
        }

        return $this->render('fahndung/index.html.twig', [
            'title' => "Fahndung erstellen",
            'form' => $form->createView(),
            'fahndung' => $fahndung,
        ]);
    }

    #[Route('/fahndung/{id<\d+>}', name: 'fahndung_show')]
    public function show(Request $request, int $id): Response
    {
        // Daten aus InMemoryDataService laden
        $fahndungData = $this->dataService->findFahndung($id);

        if (!$fahndungData) {
            $this->addFlash('error', 'Fahndung mit der ID ' . $id . ' wurde nicht gefunden.');
            return $this->redirectToRoute('fahndung_search');
        }

        // Formular erstellen (zum Anzeigen der Daten)
        $form = $this->createForm(FahndungType::class, null, [
            'data' => $fahndungData
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Im Demo-Modus keine Änderungen speichern
            $this->addFlash('info', 'Im Demo-Modus können keine Änderungen gespeichert werden.');
        }

        return $this->render('fahndung/index.html.twig', [
            'title' => 'Fahndung - Datensatz ansehen und bearbeiten',
            'form' => $form->createView(),
            'fahndung' => $fahndungData,
        ]);
    }

    #[Route('/fahndung/search', name: 'fahndung_search')]
    public function search(Request $request): Response
    {
        $form = $this->createForm(FahndungSearchType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('fahndung/search.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $formData = $form->getData();

        if (!empty($formData['vorgangsnummer']) && strlen($formData['vorgangsnummer']) < 11) {
            $this->addFlash('error', 'Die Vorgangsnummer muss mindestens 11 Zeichen lang sein.');
            return $this->redirectToRoute('fahndung_search');
        }

        $page = $request->query->getInt('page', 1);
        $resultsPerPage = 15;

        // Alle Fahndungen aus dem InMemory-Service holen
        $allResults = $this->dataService->findAllFahndungen();

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
            
            // Vorname-Filter
            if (!empty($formData['vorname'])) {
                if (stripos($item['vorname'] ?? '', $formData['vorname']) === false) {
                    return false;
                }
            }
            
            // Geburtsdatum-Filter
            if (!empty($formData['gebdatum'])) {
                if (isset($item['gebdatum']) && $item['gebdatum'] !== $formData['gebdatum']) {
                    return false;
                }
            }
            
            return true;
        });

        if (empty($filteredResults)) {
            $this->addFlash('error', 'Keine Ergebnisse gefunden.');
            return $this->redirectToRoute('fahndung_search');
        }

        // Manuelle Paginierung
        $totalResults = count($filteredResults);
        $totalPages = (int) ceil($totalResults / $resultsPerPage);
        $offset = ($page - 1) * $resultsPerPage;
        $results = array_slice($filteredResults, $offset, $resultsPerPage);

        return $this->render('fahndung/search_results.html.twig', [
            'fahndungen' => $results,
            'deleteRoute' => 'fahndung_delete',
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }

    #[Route('/fahndung/{id<\d+>}/delete', name: 'fahndung_delete')]
    public function delete(int $id): Response
    {
        // Bei InMemory-Daten können wir nichts löschen
        $this->addFlash('info', 'Im Demo-Modus können keine Einträge gelöscht werden.');
        return $this->redirectToRoute('fahndung_search');
    }
}