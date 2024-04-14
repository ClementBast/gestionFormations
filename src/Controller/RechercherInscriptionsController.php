<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\RechercheInscriptionsType;
use App\Repository\InscriptionRepository;
use App\Entity\Employe;
use App\Entity\Formation;

class RechercherInscriptionsController extends AbstractController
{
    #[Route('/rechercher/inscriptions', name: 'app_rechercher_inscriptions')]
    public function rechInscriptionsEmploye(Request $request, InscriptionRepository $inscriptionRepository): Response
    {
        $form = $this->createForm(RechercheInscriptionsType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $nom = $data['nom'];
            $prenom = $data['prenom'];

            $inscriptions = $inscriptionRepository->rechercherInscriptionsParNomEtPrenom($nom, $prenom);
            

            return $this->render('rechercher_inscriptions/rechercheInscription.html.twig', [
                'controller_name' => 'RechercherInscriptionsController',
                'form' => $form->createView(),
                'inscriptions' => $inscriptions,
            ]);
        }

      
        return $this->render('rechercher_inscriptions/rechercheInscription.html.twig', [
            'controller_name' => 'RechercherInscriptionsController',
            'form' => $form->createView(),
        ]);
    }
    #[Route('/formationsProduit/{id}', name: 'app_produit_formation_id')]
    public function afficherInscriptionsFormation($id, InscriptionRepository $inscriptionRepository, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        // Récupérer la formation par son identifiant
        $formation =$entityManager->getRepository(Formation::class)->find($id);

        // Vérifier si la formation existe
        if (!$formation) {
            throw $this->createNotFoundException('La formation demandée n\'existe pas.');
        }

        // Récupérer les inscriptions associées à cette formation
        $inscriptions = $inscriptionRepository->findBy(['formation' => $formation]);

        // Rendre le template pour afficher les inscriptions
        return $this->render('rechercher_inscriptions/rechercherProduitInscriptions.html.twig', [
            'controller_name' => 'RechercherInscriptionsController',
            'formation' => $formation,
            'inscriptions' => $inscriptions,
        ]);
    }
}
?>