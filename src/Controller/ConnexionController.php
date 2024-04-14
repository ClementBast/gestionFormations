<?php

namespace App\Controller;

use App\Entity\Employe;
use Psr\Log\LoggerInterface;
use App\Form\ConnexionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

class ConnexionController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/deconnexion', name: 'app_logout')]
    public function logout(SessionInterface $session)
    {
        $session->invalidate(); 
        return $this->redirectToRoute('app_login');
    }

    #[Route('/connexion', name: 'app_login')]
    public function connexion(Request $request, ManagerRegistry $doctrine)
    {
        date_default_timezone_set('Europe/Paris');
        $form = $this->createForm(ConnexionType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $loginForm = $data->getLogin(); // Modification ici
            $mdpForm = $data->getMdp(); // Modification ici
    
            $employe = $doctrine->getManager()->getRepository(Employe::class)->findOneBy(['login' => $loginForm]);
                if ($employe) {
                if (password_verify($mdpForm, $employe->getMdp())) {
                    $request->getSession()->set('user', [
                        'id' => $employe->getId(),
                        'login' => $employe->getLogin(),
                        'nom' => $employe->getNom(),
                        'prenom' => $employe->getPrenom(),
                    ]);
                    $this->logger->info('Connexion réussie pour l\'utilisateur ' . $employe->getNom()." " . $employe->getNom() . ' à ' . date('Y-m-d H:i'));
                    if ($employe->getStatut() == 0) {
                        return $this->redirectToRoute('app_form');
                    } else {
                        return $this->redirectToRoute('app_formEmpl');
                    }
                } else {
                    $this->addFlash('error', 'Nom d\'utilisateur ou mot de passe incorrect');
                }
            } else {
                $this->addFlash('error', 'Nom d\'utilisateur ou mot de passe incorrect');
            }
        }
    
        return $this->render('connexion/index.html.twig', [
            'form' => $form->createView(),
        ]);
    
    }
}
