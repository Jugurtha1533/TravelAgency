<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Entity\ReservationsDetails;
use App\Repository\CircuitsRepository;
use App\Repository\ReservationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'app_payment')]
    public function index(SessionInterface $session, CircuitsRepository $circuitsRepository): Response
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute("app_login");
        }

        //Si package vide rediriger vers les packages
        $panier = $session->get("panier", []);
        if (empty($panier)) {
            return $this->redirectToRoute("app_home", ['_fragment' => 'packages']);
        }

        $panier = $session->get("panier", []);
        $produits = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $circuits = $circuitsRepository->find($id);           
            $total += $circuits->getPrice() * $quantite;
            $produits[] = $circuits->getName();
        }

        $produits = join(', ', $produits);
        
        return $this->render('payment/index.html.twig', [
            'total' => $total,
            'produits' => $produits
        ]);
    }
    #[Route('/make_payment', name: 'app_make_payment')]
    public function makePayment(
        SessionInterface $session,
        Security $security,
        CircuitsRepository $circuitsRepository,
        ReservationsRepository $resaRepo
    ){
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute("app_login");
        }

        //Si package vide rediriger vers les packages
        $panier = $session->get("panier", []);
        if (empty($panier)) {
            return $this->redirectToRoute("app_home", ['_fragment' => 'packages']);
        }
        
        
        $panier = $session->get("panier", []);

        $total = 0;
        $user = $security->getUser();
        $resa = new Reservations();
        $resa
            ->setUsers($user);

        foreach($panier as $id => $quantite) {

            $reservationDetails = new ReservationsDetails();
            $circuit = $circuitsRepository->find($id);
            $price = $circuit->getPrice() * $quantite;
            $reservationDetails
            ->setReservations($resa)
            ->setQuantity($quantite)
            ->setcircuits($circuit)
            ->setPrice($price);

            $resa->addReservationsDetail($reservationDetails);
            $total += $price;
        }
         
        $resa->setTotal($total);
        
        $resaRepo->save($resa);
        $ref = 'ref_' . $user->getId() . '_' . time();
        $resa->setReference($ref);
        $resaRepo->save($resa, true);

        //vider le panier
        $session->remove("panier");
        $session->remove("cartSize");

        $this->addFlash('success', 'Votre commande a bien été prise en compte');
        return $this->redirectToRoute("app_home", ['_fragment' => 'packages']);
    }
}
