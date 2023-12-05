<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JoueurRepository;
use Doctrine\ORM\EntityManagerInterface; // Ajoutez l'importation de la classe EntityManagerInterface

class JoueurController extends AbstractController
{
    #[Route('/joueur', name: 'app_joueur')]
    public function index(): Response
    {
        return $this->render('joueur/index.html.twig', [
            'controller_name' => 'JoueurController',
        ]);
    }
    
    #[Route("/joueur/{id}", name: "afficher_joueur")]
    public function afficherJoueur($id, JoueurRepository $joueurRepository): Response
    {
        $joueur = $joueurRepository->find($id);

        if (!$joueur) {
            throw $this->createNotFoundException('Joueur non trouvé.');
        }

        return $this->render('joueur/afficher.html.twig', ['joueur' => $joueur]);
    }

    #[Route("/joueurs", name: "liste_joueurs")]
    public function listeJoueurs(JoueurRepository $joueurRepository): Response
    {
        $joueurs = $joueurRepository->findAll();

        return $this->render('joueur/liste.html.twig', ['joueurs' => $joueurs]);
    }

    #[Route("/supprimer-gardiens", name: "supprimer_gardiens")]
    public function supprimerGardiens(JoueurRepository $joueurRepository, EntityManagerInterface $entityManager): Response
    {
        $gardiens = $joueurRepository->findBy(['role' => 'gardien']);
    
        foreach ($gardiens as $gardien) {
            $entityManager->remove($gardien);
        }
    
        $entityManager->flush();
    
        return $this->redirectToRoute('liste_joueurs');
    }
}