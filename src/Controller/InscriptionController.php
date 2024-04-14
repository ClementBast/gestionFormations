<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Inscription;
use App\Entity\Formation;
use App\Entity\Employe;

class InscriptionController extends AbstractController
{
    #[Route('/inscrire/{id}', name: 'app_inscrire')]
    public function inscrire(Request $request, $id, ManagerRegistry $doctrine, SessionInterface $session): Response
    {
        $entityManager = $doctrine->getManager();

        $formation = $entityManager->getRepository(Formation::class)->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('Il n\'y a aucune formation à venir.');
        }

        $userInfo = $session->get('user');

         if (!$this->isUserConnected($session)) {
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }

        $user = $entityManager->getRepository(Employe::class)->find($userInfo['id']);

        if (!$user) {
            throw new \RuntimeException('Utilisateur introuvable dans la base de données.');
        }

        $existingInscription = $entityManager->getRepository(Inscription::class)
            ->findOneBy(['employe' => $user, 'formation' => $formation]);

        if ($existingInscription) {
            if ($existingInscription->getStatut() === 'En attente') {
                $this->addFlash('warning', 'Vous avez déjà fait une demande pour cette formation.');
            } elseif ($existingInscription->getStatut() === 'Acceptée') {
                $this->addFlash('warning', 'Vous êtes déjà inscrit à cette formation.');
            }
            
            return $this->redirectToRoute('app_formEmpl');
        }

        $inscription = new Inscription();
        $inscription->setEmploye($user);
        $inscription->setFormation($formation);
        $inscription->setStatut('En attente');

        $entityManager->persist($inscription);
        $entityManager->flush();

        $this->addFlash('success', 'Inscription réussie à la formation ' . $formation->getProduit()->getLibelle());

        return $this->redirectToRoute('app_formEmpl');
    }
    private function isUserConnected(SessionInterface $session): bool
    {
        $userInfo = $session->get('user');
        return $userInfo && isset($userInfo['id']);
        
    }
}
?>