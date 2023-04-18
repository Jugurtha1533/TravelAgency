<?php

namespace App\Controller;

use App\Entity\Circuits;
use App\Repository\CircuitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cart', name:'cart_')]
class CartController extends AbstractController
{
    #[Route('/', name:'index')]
    public function index(SessionInterface $session, CircuitsRepository $CircuitsRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $circuits = $CircuitsRepository->find($id);
            $dataPanier[] = [
                "circuit" => $circuits,
                "quantite" => $quantite
            ];
           
            $total += $circuits->getPrice() * $quantite/100;
        }

        return $this->render('cart/index.html.twig', compact("dataPanier", "total"));
       
    }
    #[Route('/add/{id}', name:'add')]
    public function add(Circuits $circuits, SessionInterface $session)
    {

        try {
            // throw new \Exception("on a une exception");
            // On récupère le panier actuel
            $panier = $session->get("panier", []);
            $id = $circuits->getId();
        

            if(!empty($panier[$id])){
                $panier[$id]++;
            
        
            }else{
                $panier[$id] =1;
            }

            // On sauvegarde dans la session
            $session->set("panier", $panier);
        
            return $this->redirectToRoute("cart_index");

        } catch (\Exception $e) {
            echo $e->getMessage();
            die;

        }
        
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Circuits $circuits, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $circuits->getId();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Circuits $circuits, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $circuits->getId();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("cart_index");
    }

}