<?php
// src/Controller/ZielfahndungController.php

namespace App\Controller;

use App\Form\ZielfahndungSearchType;
use App\Service\KriteriumService;
use App\Service\QueryBuilderService;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\MappingException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Zielfahndung;
use App\Form\ZielfahndungType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ZielfahndungRepository;

//TODO: Routen schützen zB #[IsGranted('IS_AUTHENTICATED_FULLY')]
//TODO: evtl. in der search()-Methode das Feld "Inhalt des Schreibens" bereits in der Suchergebnisse-Liste anzeigen lassen (LKA-Wunsch)
class ZielfahndungController extends AbstractController
{   // Repository und Services werden als private Variablen deklariert
    private ZielfahndungRepository $zielfahndungRepository;
    private EntityManagerInterface $entityManager;
    private KriteriumService $kriteriumService; // Hinzufügen der KriteriumService-Instanz

    // Konstruktor: Services und Repository werden über Dependency Injection eingefügt
    public function __construct(
        ZielfahndungRepository $zielfahndungRepository,
        EntityManagerInterface $entityManager,
        KriteriumService $kriteriumService, // Injection des KriteriumService
        QueryBuilderService $queryBuilderService

    ) { // Zuweisung der übergebenen Services und Repository
        $this->zielfahndungRepository = $zielfahndungRepository;
        $this->entityManager = $entityManager;
        $this->kriteriumService = $kriteriumService; // Zuweisung der KriteriumService-Instanz
        $this->queryBuilderService = $queryBuilderService;
    }

    //TODO: Routen schützen in allen Controllern
    /**
     * @throws ORMException
     * @throws \Exception
     */
    #[Route('/zielfahndung/new', name: 'zielfahndung')]
    public function new(Request $request): Response
    {    // Neues Zielfahndungsobjekt wird erstellt
        $zielfahndung = new Zielfahndung();

        // Vorgangsnummer generieren über Repository
        $zielfahndung->setVorgangsnummer($this->zielfahndungRepository->generateVorgangsnummer());
        if (empty($zielfahndung->getVorgangsnummer())) {
            // Fehlerbehandlung, falls keine Vorgangsnummer generiert werden konnte
            throw new \Exception('Vorgangsnummer konnte nicht generiert werden.');
        }
        // Erstellen und Bearbeiten des Formulars für Zielfahndung
        $form = $this->createForm(ZielfahndungType::class, $zielfahndung);
        $form->handleRequest($request);
        // Wenn das Formular gesendet und gültig ist, wird die Datenbankoperation durchgeführt
        if ($form->isSubmitted() && $form->isValid()) {
            // Start der Transaktion
            $this->entityManager->beginTransaction();

            try {
                // Persistieren und die Änderungen werden automatisch in einer Transaktion durchgeführt
                $this->entityManager->persist($zielfahndung);
                $this->entityManager->flush();

                // Transaktion erfolgreich abschließen
                $this->entityManager->commit();

                $this->addFlash('success', 'Daten erfolgreich gespeichert!');
                return $this->redirectToRoute('zielfahndung_show', ['id' => $zielfahndung->getId()]);
            } catch (\Exception $e) {
                // Bei einem Fehler wird die Transaktion zurückgerollt
                $this->entityManager->rollback();
                $this->addFlash('error', 'Fehler beim Speichern der Daten. Bitte versuchen Sie es erneut.');
            }
        }

        return $this->render('zielfahndung/index.html.twig', [
            'title' => "Zielfahndung erstellen",
            'form' => $form->createView(),
            'zielfahndung' => $zielfahndung,
        ]);
    }

    #[Route('/zielfahndung/{id<\d+>}', name: 'zielfahndung_show')]
    public function show(Request $request, int $id): Response
    {    // Zielfahndung anhand der ID aus dem Repository laden
        $zielfahndung = $this->zielfahndungRepository->find($id);
        // Falls die Zielfahndung nicht gefunden wurde, wird eine Fehlermeldung angezeigt
        if (!$zielfahndung) {
            $this->addFlash('error', 'Zielfahndung mit der ID ' . $id . ' wurde nicht gefunden.');
            return $this->redirectToRoute('zielfahndung_search');
        }
        // Erstellen und Bearbeiten des Formulars für Zielfahndung
        $form = $this->createForm(ZielfahndungType::class, $zielfahndung);
        $form->handleRequest($request);
        // Wenn das Formular gesendet und gültig ist, werden die Änderungen gespeichert
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($zielfahndung);
            $this->entityManager->flush();

            $this->addFlash('success', 'Änderungen erfolgreich gespeichert!');
        }
        // Anzeige des Formulars
        return $this->render('zielfahndung/index.html.twig', [
            'title' => "Zielfahndung - Datensatz ansehen und bearbeiten",
            'form' => $form->createView(),
            'zielfahndung' => $zielfahndung,
        ]);
    }
    /** Methode zum Suchen von Zielfahndungen.
     * Wird nicht direkt genutzt sondern im Service-Workflow über den QueryBuilderService
     * und dessen Methode createQueryBuilder() aufgerufen
     * @throws MappingException
     * @throws ORMException
     */
    #[Route('/zielfahndung/search', name: 'zielfahndung_search')]
    public function search(Request $request,
                           QueryBuilderService $queryBuilderService): Response
    {
        // Erstellt das Formular zur Suche von Zielfahndungen
        $form = $this->createForm(ZielfahndungSearchType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('zielfahndung/search.html.twig', [
                'form' => $form->createView(),
            ]);
        }
        //$formData gibt ass.Array, getData gibt ein Array zurück
        $formData = $form->getData();
        //dump($formData);
        //dump(gettype($formData));
        //dd($formData instanceof Zielfahndung); gibt "false" zurück (kein Objekt)

        if (!empty($formData['vorgangsnummer']) && strlen($formData['vorgangsnummer']) < 11) {
            $this->addFlash('error', 'Die Vorgangsnummer muss mindestens 11 Zeichen lang sein.');
            return $this->redirectToRoute('zielfahndung_search');
        }

        $criteria = $this->kriteriumService->createKriterien($formData, Zielfahndung::class);
        //dump($criteria);

        if (empty($criteria)) {
            $this->addFlash('error', 'Bitte mindestens eine Angabe machen.');
            return $this->redirectToRoute('zielfahndung_search');
        }
        // Standardmäßig auf Seite 1 setzen
        //Wert für die Seite aus der URL-Anfrage extrahieren und in der Variable $page speichern
        $page = $request->query->getInt('page', 1);
        $resultsPerPage = 15;

        // Paginierung und Ergebnisse über den Service erhalten
        $resultData = $queryBuilderService->getPaginatedResults(Zielfahndung::class, 'z', $criteria, $page, $resultsPerPage);

        //Dynamischer Aufruf der Entity "Zielfahndung" aus dem QueryBuilderService mit 'results'
        if (!empty($resultData['results'])) {
            return $this->render('zielfahndung/search_results.html.twig', [
                // Dem dynamischen Key 'results' werden die tatsächlichen "ziefahndungen" zugewiesen
                'zielfahndungen' => $resultData['results'],
                'deleteRoute' => 'zielfahndung_delete',
                'currentPage' => $page,
                'totalPages' => $resultData['totalPages'],
            ]);
        } else {
            $this->addFlash('error', 'Keine Ergebnisse gefunden.');
            return $this->redirectToRoute('zielfahndung_search');
        }
    }

    #[Route('/zielfahndung/{id}/delete', name: 'zielfahndung_delete')]
    public function delete(int $id): Response
    {
        $zielfahndung = $this->zielfahndungRepository->find($id);

        if (!$zielfahndung) {
            $this->addFlash('error', 'Zielfahndung wurde nicht gefunden.');
            return $this->redirectToRoute('zielfahndung_search');
        }

        $this->entityManager->remove($zielfahndung);
        $this->entityManager->flush();

        $this->addFlash('success', 'Zielfahndung wurde gelöscht!');
        return $this->redirectToRoute('zielfahndung_search');

    }


}