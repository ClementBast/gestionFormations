<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Employe;
use App\Repository\InscriptionRepository;
use Doctrine\Persistence\ManagerRegistry;

class RechercherEmployeController extends AbstractController
{
    #[Route('/rechercher/employe', name: 'app_rechercher_employe')]
    public function listeEmploye(ManagerRegistry $doctrine, InscriptionRepository $inscriptionRepository): Response
    {
        // Récupérer le repository de l'entité Employe
        $employeRepository = $doctrine->getRepository(Employe::class);

        // Récupérer tous les employés avec le statut 0
        $employes = $employeRepository->findBy(['statut' => 0]);

        // Créer un tableau pour stocker les données des employés et de leurs inscriptions
        $employesData = [];

        // Pour chaque employé, récupérer les inscriptions correspondantes
        foreach ($employes as $employe) {
            // Récupérer les inscriptions de l'employé en utilisant son nom et prénom
            $inscriptions = $inscriptionRepository->rechercherInscriptionsParNomEtPrenom($employe->getNom(), $employe->getPrenom());

            // Créer un tableau pour stocker les inscriptions de l'employé
            $employeData = [
                'nom' => $employe->getNom(),
                'prenom' => $employe->getPrenom(),
                'inscriptions' => $inscriptions, // Stocker les inscriptions directement
            ];

            // Ajouter les données de l'employé au tableau principal
            $employesData[] = $employeData;
        }

        // Rendre la vue en passant les données des employés et de leurs inscriptions
        return $this->render('rechercher_employe/listeEmploye.html.twig', [
            'employes' => $employesData,
        ]);
    }
}
