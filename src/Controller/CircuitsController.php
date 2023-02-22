<?php

namespace App\Controller;

use App\Entity\Circuits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/circuits', name: 'circuits_')]
class CircuitsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('circuits/index.html.twig');
    }

    #[Route('/{slug}', name: 'details')]
    public function details(Circuits $product): Response
    {
        return $this->render('circuits/details.html.twig', compact('circuit'));
    }
}