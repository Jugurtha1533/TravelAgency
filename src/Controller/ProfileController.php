<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name:'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Profile de l\'utilisateur',
        ]);
    }

    #[Route('/reservations', name: 'reservation')]
    public function reservations(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'Reservations de l\'utilisateur',
        ]);
    }
}
