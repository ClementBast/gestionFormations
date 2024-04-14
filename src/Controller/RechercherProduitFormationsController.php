<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RechercherProduitsFormationsType;

use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface; // Ajout de l'import pour l'EntityManagerInterface

class RechercherProduitFormationsController extends AbstractController
{
    private $entityManager; // Ajout de la propriété $entityManager

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/formationsProduits', name: 'app_produit_formations')]
    public function rechercherProduitsFormations(Request $request, ProduitRepository $produitRepository): Response
    {
        // Récupérer le produit par son nom depuis la requête
        $libelle = $request->query->get('libelle');
    
        // Créer le formulaire en pré-remplissant le champ 'libelle' avec la valeur du nom du produit
        $form = $this->createForm(RechercherProduitsFormationsType::class, ['libelle' => $libelle]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $libelle = $data['libelle'];
        
            // Utilisation de la méthode personnalisée du ProduitRepository pour obtenir les inscriptions par nom de produit
            $inscriptions = $produitRepository->rechercherInscriptionsParNomProduit($libelle);
        
            return $this->render('rechercher_produit_formations/produitsFormations.html.twig', [
                'controller_name' => 'RechercherProduitFormationsController',
                'form' => $form->createView(),
                'inscriptions' => $inscriptions,
            ]);
        }
    
        // Retourner le formulaire si celui-ci n'est pas soumis ou s'il n'est pas valide
        return $this->render('rechercher_produit_formations/produitsFormations.html.twig', [
            'controller_name' => 'RechercherProduitFormationsController',
            'form' => $form->createView(),
        ]);
    }
    /*
    #[Route('/formationsProduit/{id}', name: 'app_produit_formation_id')]
    public function rechercherProduitsFormationsById($id, FormationRepository $formationRepository): Response
    {
        // Récupérer la formation par son identifiant depuis le repository
        $formation = $formationRepository->find($id);
    
        if (!$formation) {
            throw $this->createNotFoundException('La formation demandée n\'existe pas.');
        }
    
        // Vous pouvez maintenant utiliser la formation récupérée pour effectuer d'autres traitements ou affichages
    
        return $this->render('rechercher_produit_formations/rechercherProduitFormations.html.twig', [
            'controller_name' => 'RechercherProduitFormationsController',
            'formation' => $formation,
        ]);
    }
    */
    

}
