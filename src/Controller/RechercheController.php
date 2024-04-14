<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Inscription;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry; // Ajout de l'import nécessaire

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'app_recherche')]
    public function index(): Response
    {
        return $this->render('recherche/index.html.twig', [
            'controller_name' => 'RechercheController',
        ]);
    }

    #[Route('/recherches', name: 'app_recherche')]
    public function rechercheFindBy(ManagerRegistry $doctrine)
    {
        $employes = $doctrine->getRepository(Employe::class)->findBy([ 'statut' => '0']);

        return $this->render('recherche/employes.html.twig', [
            'ensEmployes' => $employes,
        ]);
    }
}
