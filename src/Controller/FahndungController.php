<?php
namespace App\Controller;

use App\Form\FahndungSearchType;
use App\Repository\FahndungRepository;
use App\Service\KriteriumService;
use App\Service\QueryBuilderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Fahndung;
use App\Form\FahndungType;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\SearchService;

class FahndungController extends AbstractController
{
    private FahndungRepository $fahndungRepository;
    private EntityManagerInterface $entityManager;
    private KriteriumService $kriteriumService;

    public function __construct(FahndungRepository $fahndungRepository,
                                EntityManagerInterface $entityManager,
                                KriteriumService $kriteriumService,
                                QueryBuilderService $queryBuilderService)

    {
        $this->fahndungRepository = $fahndungRepository;
        $this->entityManager = $entityManager;
        $this->kriteriumService = $kriteriumService;
        $this->queryBuilderService = $queryBuilderService;
    }

    #[Route('/fahndung/new', name: 'fahndung')]
    public function new(Request $request): Response
    {
        $fahndung = new Fahndung();

        // Vorgangsnummer generieren über Repository
        $fahndung->setVorgangsnummer($this->fahndungRepository->generateVorgangsnummer());
        if (empty($fahndung->getVorgangsnummer())) {
            // Fehlerbehandlung, falls keine Vorgangsnummer generiert werden konnte
            throw new \Exception('Vorgangsnummer konnte nicht generiert werden.');
        }

        $form = $this->createForm(FahndungType::class, $fahndung);
        $form->handleRequest($request);

        // Wenn das Formular gesendet und gültig ist, wird die Datenbankoperation durchgeführt
        if ($form->isSubmitted() && $form->isValid()) {
            // Start der Transaktion
            $this->entityManager->beginTransaction();

            try {
                // Persistieren und die Änderungen werden automatisch in einer Transaktion durchgeführt
                $this->entityManager->persist($fahndung);
                $this->entityManager->flush();

                // Transaktion erfolgreich abschließen
                $this->entityManager->commit();

                $this->addFlash('success', 'Daten erfolgreich gespeichert!');
                return $this->redirectToRoute('fahndung_show', ['id' => $fahndung->getId()]);
            } catch (\Exception $e) {
                // Bei einem Fehler wird die Transaktion zurückgerollt
                $this->entityManager->rollback();
                $this->addFlash('error', 'Fehler beim Speichern der Daten. Bitte versuchen Sie es erneut.');
            }
        }
        return $this->render('fahndung/index.html.twig', [
            'title' => "Fahndung erstellen",
            'form' => $form->createView(),
            'fahndung' => $fahndung,
        ]);
    }

    #[Route('/fahndung/{id<\d+>}', name: 'fahndung_show')]
    public function show(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $fahndung = $this->fahndungRepository->find($id);
        if (!$fahndung) {
            $this->addFlash('error', 'Fahndung mit der ID ' . $id . ' wurde nicht gefunden.');
            return $this->redirectToRoute('fahndung_search');
        }

        $form = $this->createForm(FahndungType::class, $fahndung);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fahndung);
            $entityManager->flush();

            $this->addFlash('success', 'Änderungen erfolgreich gespeichert!');
        }

        return $this->render('fahndung/index.html.twig', [
            'title' => 'Fahndung - Datensatz ansehen und bearbeiten',
            'form' => $form->createView(),
            'fahndung' => $fahndung,
        ]);
    }

    #[Route('/fahndung/search', name: 'fahndung_search')]
    public function search(Request $request, QueryBuilderService $queryBuilderService): Response
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

        $criteria = $this->kriteriumService->createKriterien($formData, Fahndung::class);

        if (empty($criteria)) {
            $this->addFlash('error', 'Bitte mindestens eine Angabe machen.');
            return $this->redirectToRoute('fahndung_search');
        }
        // Standardmäßig auf Seite 1 setzen
        $page = $request->query->getInt('page', 1);
        $resultsPerPage = 15;

        // Paginierung und Ergebnisse über den Service abrufen
        $resultData = $queryBuilderService->getPaginatedResults(Fahndung::class, 'f', $criteria, $page, $resultsPerPage);

        //Dynamischer Aufruf der Entity "Fahndung" aus dem QueryBuilderService mit 'results'
        if (!empty($resultData['results'])) {
            return $this->render('fahndung/search_results.html.twig', [
                // Dem dynamischen Key 'results' werden die tatsächlichen "fahndungen" zugewiesen
                'fahndungen' => $resultData['results'],
                'deleteRoute' => 'fahndung_delete',
                'currentPage' => $page,
                'totalPages' => $resultData['totalPages'],
            ]);
        } else {
            $this->addFlash('error', 'Keine Ergebnisse gefunden.');
            return $this->redirectToRoute('fahndung_search');
        }
    }


    #[Route('/fahndung/{id<\d+>}/delete', name: 'fahndung_delete')]
    public function delete(int $id): Response
    {
        // Hole das Fahndung-Repository direkt
        $fahndung = $this->fahndungRepository->find($id);

        if (!$fahndung) {
            $this->addFlash('error', 'Fahndung mit der ID ' . $id . ' wurde nicht gefunden.');
            return $this->redirectToRoute('fahndung_search');
        }

        // Lösche die Entität
        $this->entityManager->remove($fahndung);
        $this->entityManager->flush();

        // Bestätigungsnachricht
        $this->addFlash('success', 'Eintrag erfolgreich gelöscht.');

        return $this->redirectToRoute('fahndung_search');
    }


}
