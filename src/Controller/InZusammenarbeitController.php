<?php
namespace App\Controller;

use App\Form\IntzusammenarbeitSearchType;
use App\Repository\IntZusammenarbeitRepository;
use App\Service\KriteriumService;
use App\Service\QueryBuilderService;
use App\Service\SearchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\IntZusammenarbeit;
use App\Form\IntzusammenarbeitType;
use Doctrine\ORM\EntityManagerInterface;

class IntzusammenarbeitController extends AbstractController
{
    private IntZusammenarbeitRepository $intZusammenarbeitRepository;
    private EntityManagerInterface $entityManager;
    private KriteriumService $kriteriumService;

    public function __construct(
        IntZusammenarbeitRepository $intZusammenarbeitRepository,
        EntityManagerInterface     $entityManager,
        KriteriumService $kriteriumService,
    )
    {
        $this->intZusammenarbeitRepository = $intZusammenarbeitRepository;
        $this->entityManager = $entityManager;
        $this->kriteriumService = $kriteriumService;
    }

    #[Route('/intzusammenarbeit/new', name: 'intzusammenarbeit')]
    public function new(Request $request): Response
    {
        $intzusammenarbeit = new IntZusammenarbeit();

        // Vorgangsnummer generieren über Repository
        $intzusammenarbeit->setVorgangsnummer($this->intZusammenarbeitRepository->generateVorgangsnummer());
        if (empty($intzusammenarbeit->getVorgangsnummer())) {
            // Fehlerbehandlung, falls keine Vorgangsnummer generiert werden konnte
            throw new \Exception('Vorgangsnummer konnte nicht generiert werden.');
        }

        $form = $this->createForm(IntzusammenarbeitType::class, $intzusammenarbeit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Start der Transaktion
            $this->entityManager->beginTransaction();

            try {
                // Persistieren und die Änderungen werden automatisch in einer Transaktion durchgeführt
                $this->entityManager->persist($intzusammenarbeit);
                $this->entityManager->flush();

                // Transaktion erfolgreich abschließen
                $this->entityManager->commit();

                $this->addFlash('success', 'Daten erfolgreich gespeichert!');
                return $this->redirectToRoute('intzusammenarbeit_show', ['id' => $intzusammenarbeit->getId()]);
            } catch (\Exception $e) {
                // Bei einem Fehler wird die Transaktion zurückgerollt
                $this->entityManager->rollback();
                $this->addFlash('error', 'Fehler beim Speichern der Daten. Bitte versuchen Sie es erneut.');
            }
}
        return $this->render('intzusammenarbeit/index.html.twig', [
            'title' => "Int. Zusammenarbeit erstellen",
            'form' => $form->createView(),
            'intzusammenarbeit' => $intzusammenarbeit,
        ]);
    }

    #[Route('/intzusammenarbeit/{id<\d+>}', name: 'intzusammenarbeit_show')]
    public function show(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $intzusammenarbeit = $this->intZusammenarbeitRepository->find($id);
        if (!$intzusammenarbeit) {
            $this->addFlash('error', 'Int. Zusammenarbeit mit der ID ' . $id . ' wurde nicht gefunden.');
            return $this->redirectToRoute('intzusammenarbeit_search');
        }
        // Erstellen und Bearbeiten des Formulars
        $form = $this->createForm(IntzusammenarbeitType::class, $intzusammenarbeit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($intzusammenarbeit);
            $entityManager->flush();
            // Wenn das Formular gesendet und gültig ist, werden die Änderungen gespeichert
            $this->addFlash('success', 'Änderungen erfolgreich gespeichert!');
        }

        return $this->render('intzusammenarbeit/index.html.twig', [
            'form' => $form->createView(),
            'intzusammenarbeit' => $intzusammenarbeit,
            'title' => "Int. Zusammenarbeit - Datensatz ansehen und bearbeiten",
        ]);
    }

    //Anmerkung: Ungültige Datumsangaben wie zB 01.01.0001 werden teilweise gespeichert trotz Fehlermeldung,
    // aber bei Suche mit Operatoren teilweise nicht gefunden.
    #[Route('/intzusammenarbeit/search', name: 'intzusammenarbeit_search')]
    public function search(Request $request, QueryBuilderService $queryBuilderService): Response
    {
        // Erstellt das Formular zur Suche
        $form = $this->createForm(IntzusammenarbeitSearchType::class);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('intzusammenarbeit/search.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        $formData = $form->getData();

        if (!empty($formData['vorgangsnummer']) && strlen($formData['vorgangsnummer']) < 11) {
            $this->addFlash('error', 'Die Vorgangsnummer muss mindestens 11 Zeichen lang sein.');
            return $this->redirectToRoute('intzusammenarbeit_search');
        }

        $criteria = $this->kriteriumService->createKriterien($formData, IntZusammenarbeit::class);

        if (empty($criteria)) {
            $this->addFlash('error', 'Bitte mindestens eine Angabe machen.');
            return $this->redirectToRoute('intzusammenarbeit_search');
        }

        // Standardmäßig auf Seite 1 setzen
        $page = $request->query->getInt('page', 1);
        $resultsPerPage = 15;

        // Paginierung und Ergebnisse über den Service abrufen
        $resultData = $queryBuilderService->getPaginatedResults(IntZusammenarbeit::class, 'i', $criteria, $page, $resultsPerPage);
        //Dynamischer Aufruf der Entity "IntZusammenarbeit" aus dem QueryBuilderService mit 'results'
        if (!empty($resultData['results'])) {
            return $this->render('intzusammenarbeit/search_results.html.twig', [
                'intzusammenarbeit' => $resultData['results'],
                'deleteRoute' => 'intzusammenarbeit_delete',
                'currentPage' => $page,
                'totalPages' => $resultData['totalPages'],
            ]);
        } else {
            $this->addFlash('error', 'Keine Ergebnisse gefunden.');
            return $this->redirectToRoute('intzusammenarbeit_search');
        }
    }

    #[Route('/intzusammenarbeit/{id<\d+>}/delete', name: 'intzusammenarbeit_delete')]
    public function delete(int $id, Request $request): Response
    {
        $intzusammenarbeit = $this->intZusammenarbeitRepository->find($id);

        if (!$intzusammenarbeit) {
            $this->addFlash('error', 'Int. Zusammenarbeit nicht gefunden.');
            return $this->redirectToRoute('intzusammenarbeit_search');
        }

        // Lösche die Entität
        $this->entityManager->remove($intzusammenarbeit);
        $this->entityManager->flush();

        $this->addFlash('success', 'Int. Zusammenarbeit wurde gelöscht!');
        return $this->redirectToRoute('intzusammenarbeit_search');
    }
}
