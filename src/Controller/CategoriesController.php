<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CircuitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
    #[Route('/{slug}', name: 'list')]
    public function list(Categories $category, CircuitsRepository $circuitsRepository, Request $request): Response
    {
        //On va chercher le numéro de page dans l'url
        $page = $request->query->getInt('page', 1);

        //On va chercher la liste des circuits de la catégorie
        $circuits = $circuitsRepository->findCircuitsPaginated($page, $category->getSlug(), 4);

        return $this->render('categories/list.html.twig', compact('category', 'circuits'));
        // Syntaxe alternative
        // return $this->render('categories/list.html.twig', [
        //     'category' => $category,
        //     'circuits' => $circuits
        // ]);
    }
}