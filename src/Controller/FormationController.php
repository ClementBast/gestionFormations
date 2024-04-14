<?php

namespace App\Controller;

use App\Entity\Employe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type as SFType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\InscriptionRepository;
use App\Form\FormationType;
use App\Entity\Formation;
use App\Entity\Inscription;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Entity\Produit;
use Symfony\Component\DomCrawler\Form;

class FormationController extends AbstractController
{
    #[Route('/formation', name: 'app_formation')]
    public function index(): Response
    {
        return $this->render('formation/index.html.twig', [
            'controller_name' => 'FormationController',
        ]);
    }



    #[Route('/mesformations', name: 'mes_formations')]
public function mesFormations(InscriptionRepository $inscriptionRepository, ManagerRegistry $doctrine, SessionInterface $session): Response
{
    // Vérifiez d'abord si l'utilisateur est connecté
    if (!$session->has('user')) {
        // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
        return $this->redirectToRoute('app_login');
    }

    // Récupérer l'ID de l'utilisateur à partir de la session
    $userId = $session->get('user')['id'];

    // Récupérer les inscriptions de l'utilisateur
    $inscriptions = $inscriptionRepository->findBy(['employe' => $userId]);

    $formations = [];

    // Parcourir les inscriptions pour récupérer les formations et les statuts
    foreach ($inscriptions as $inscription) {
        $formations[] = [
            'formation' => $inscription->getFormation(),
            'statut' => $inscription->getStatut(),
        ];
    }

    return $this->render('formation/listeFormationInscEmp.html.twig', [
        'formations' => $formations,
    ]);
}

    




    #[Route('/ajoutFormation', name: 'ajout')]
    public function ajoutAction(Request $request, ManagerRegistry $doctrine, $formation = null): Response
    {
        if ($formation === null) {
            $formation = new Formation();
        }
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($formation);
            $em->flush();

           
            return $this->redirectToRoute('app_form');
        }
        return $this->render(
            'formation/editer.html.twig',
            ['form' => $form->createView()]
        );
    }
    #[Route('/afform/{id}', name: 'app_formId')]
    public function afficheFormationByIdAction($id, ManagerRegistry $doctrine)
        {
            $formation = $doctrine->getManager()->getRepository(Formation::class)->find($id);

            return $this->render('formation/uneFormation.html.twig', array('uneFormation' => $formation));
            
        }
    
       /* #[Route('/rechercherFindBy', name: 'app_recherche_finfBy')]
        public function rechercherFindBy(ManagerRegistry $doctrine)
            {
                $formation = $doctrine->getManager()->getRepository(Formation::class)->find($id);
    
                return $this->render('formation/uneFormation.html.twig', array('uneFormation' => $formation));
                
            }
            */
    
        
        #[Route('/afform', name: 'app_form')]
    public function afficheFormationAction(ManagerRegistry $doctrine): Response
    {
     
        $formations = $doctrine->getManager()->getRepository(Formation::class)->findAll();

        foreach ($formations as $formation) {
                return $this->render('formation/listeFormations.html.twig', ['formations' => $formations]);
        }
    }

    #[Route('/afformEmp', name: 'app_formEmpl')]
    public function afficheFormationEmplAction(ManagerRegistry $doctrine): Response
    {
        
        $formations = $doctrine->getManager()->getRepository(Formation::class)->findAll();

        foreach ($formations as $formation) {
                return $this->render('formation/listeFormationsEmpl.html.twig', ['formations' => $formations]);
        }
    }

        
   #[Route('/supprimerFormation/{id}', name: 'supprimer_formation')]
public function supprimerFormationAction($id, ManagerRegistry $doctrine): RedirectResponse
{
    $entityManager = $doctrine->getManager();
    $formationRepository = $entityManager->getRepository(Formation::class);
    $formation = $formationRepository->find($id);

    if (!$formation) {
        $this->addFlash('error', 'La formation n\'existe pas.');
    } else {
      
        $inscriptions = $entityManager->getRepository(Inscription::class)->findBy(['formation' => $formation]);

        if (!empty($inscriptions)) {
            $this->addFlash('error', 'Impossible de supprimer la formation car des inscriptions sont associées.');
        } else {
            try {
                // Supprimer la formation
                $entityManager->remove($formation);
                $entityManager->flush();

                $this->addFlash('success', 'La formation a été supprimée avec succès.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur s\'est produite lors de la suppression de la formation : ' . $e->getMessage());
            }
        }
    }

    return $this->redirectToRoute('app_form');
}

    
        
        
        #[Route('/modif/{id}', name: 'modif')]
        public function modifFormationAction($id, Request $request, ManagerRegistry $doctrine)
        {
            $formation = $doctrine->getRepository(Formation::class)->find($id);
            $form = $this->createForm(FormationType::class, $formation);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $doctrine->getManager();
                $em->persist($formation);
                $em->flush();
                return $this->redirectToRoute('app_form');
            }
            return $this->render(
                'formation/editer.html.twig',
                ['form' => $form->createView()]
            );
        }
        #[Route('/inscrire', name: 'app_inscrire')]
        public function inscrireFormationAction(ManagerRegistry $doctrine)
        {
            
            $repository = $doctrine->getRepository(Inscription::class);
        }

        #[Route('/demandes', name: 'app_demandes')]
        public function afficheDemandesAction(SessionInterface $session, ManagerRegistry $doctrine): Response
        {
            if (!$this->isUserConnected($session)) {
                $this->addFlash('warning', 'Vous devez être connecté pour accéder à cette page.');
                return $this->redirectToRoute('app_login');
            }
    
            $entityManager = $doctrine->getManager();
            $user = $entityManager->getRepository(Employe::class)->find($session->get('user')['id']);
    
            if (!$user) {
                throw new \RuntimeException('Utilisateur introuvable dans la base de données.');
            }
    
            $demandesFormation = $entityManager->getRepository(Inscription::class)->findAll();
    
            return $this->render('formation/demandesFormation.html.twig', [
                'demandesFormation' => $demandesFormation,
            ]);
        }
    #[Route('/accepterDemande/{id}', name: 'accepter_demande')]
public function accepterDemandeAction($id, ManagerRegistry $doctrine): Response
{
    $entityManager = $doctrine->getManager();
    $inscription = $entityManager->getRepository(Inscription::class)->find($id);

    if (!$inscription) {
        $this->addFlash('error', 'Demande de formation introuvable.');
    } else {

        $inscription->setStatut('Acceptée');
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_demandes');
}

#[Route('/refuserDemande/{id}', name: 'refuser_demande')]
public function refuserDemandeAction($id, ManagerRegistry $doctrine): Response
{
    $entityManager = $doctrine->getManager();
    $inscription = $entityManager->getRepository(Inscription::class)->find($id);

    if (!$inscription) {
        $this->addFlash('error', 'Demande de formation introuvable.');
    } else {
       
        $inscription->setStatut('Refusée');
        $entityManager->flush();
    }
    return $this->redirectToRoute('app_demandes');
}

private function isUserConnected(SessionInterface $session): bool
    {
        $userInfo = $session->get('user');
        return $userInfo && isset($userInfo['id']);
    }
}
