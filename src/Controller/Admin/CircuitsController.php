<?php

namespace App\Controller\Admin;


use App\Entity\Circuits;
use App\Entity\Categories;
use App\Entity\Images;
use App\Repository\CircuitsRepository;
use App\Form\CircuitsFormType;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;



#[Route('/admin/Circuits', name: 'admin_circuits_')]

class CircuitsController extends AbstractController
{
    #[Route('/', name: 'index')]
    
    public function index( CircuitsRepository $circuitsRepository): Response
    {
        return $this->render('admin/Circuits/index.html.twig',[
            'circuits'=>$circuitsRepository->findBy([]) 
           
          ]);

     
        
    }

    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //On crée un "nouveau produit"
        $circuit = new Circuits();

        // On crée le formulaire
        $circuitForm = $this->createForm(CircuitsFormType::class, $circuit);

        // On traite la requête du formulaire
        $circuitForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($circuitForm->isSubmitted() && $circuitForm->isValid()){
            // On récupère les images
            $images = $circuitForm->get('images')->getData();
            
            foreach($images as $image){
                // On définit le dossier de destination
                $folder = 'circuits';

                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Images();
                $img->setName($fichier);
                $circuit->addImage($img);
            }

            // On génère le slug
            $slug = $slugger->slug($circuit->getName());
            $circuit->setSlug($slug);

            // On arrondit le prix 
            //$prix = $product->getPrice() * 100;
           // $product->setPrice($prix);

            // On stocke
            $em->persist($circuit);
            $em->flush();

            $this->addFlash('success', 'Circuit ajouté avec succès');

            // On redirige
            return $this->redirectToRoute('admin_circuits_index');
        }


         return $this->render('admin/Circuits/add.html.twig',[
           'circuitsForm' => $circuitForm->createView()
      ]);

       // return $this->renderForm('admin/product/add.html.twig', compact('productForm'));
        // ['productForm' => $productForm]
    }

    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Circuits $circuit, Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        // On vérifie si l'utilisateur peut éditer avec le Voter
        //$this->denyAccessUnlessGranted('CIRCUIT_EDIT', $circuits);

        // On divise le prix par 100
       // $prix = $product->getPrice() / 100;
       // $product->setPrice($prix);

        // On crée le formulaire
        $circuitForm = $this->createForm(CircuitsFormType::class, $circuit);

        // On traite la requête du formulaire
        $circuitForm->handleRequest($request);

        //On vérifie si le formulaire est soumis ET valide
        if($circuitForm->isSubmitted() && $circuitForm->isValid()){
            // On récupère les images
            $images = $circuitForm->get('images')->getData();

            foreach($images as $image){
                // On définit le dossier de destination
                $folder = 'circuits';

                // On appelle le service d'ajout
                $fichier = $pictureService->add($image, $folder, 300, 300);

                $img = new Images();
                $img->setName($fichier);
                $circuit->addImage($img);
            }
            
            
            // On génère le slug
            $slug = $slugger->slug($circuit->getName());
            $circuit->setSlug($slug);

            // On arrondit le prix 
           // $prix = $product->getPrice() * 100;
           // $product->setPrice($prix);

            // On stocke
            $em->persist($circuit);
            $em->flush();

            $this->addFlash('success', 'Circuit modifié avec succès');

            // On redirige
            return $this->redirectToRoute('admin_circuits_index');
        }


        return $this->render('admin/Circuits/edit.html.twig',[
            'circuitsForm' => $circuitForm->createView(),
            'circuits' => $circuit
        ]);

        // return $this->renderForm('admin/products/edit.html.twig', compact('productForm'));
        // ['productForm' => $productForm]
    }

    #[Route('/suppression/{id}', name: 'delete', methods:['GET'])]
    public function delete(EntityManagerInterface $manager,Circuits $circuit): Response
    {
        
        // On vérifie si l'utilisateur peut supprimer avec le Voter
       //$this->denyAccessUnlessGranted('CIRCUIT_DELETE', $circuits,);

        $manager->remove($circuit);
        $manager->flush();
          
        $this->addFlash('success', ' Votre Produit à été supprimé avec succès');
       
        return $this->redirectToRoute('admin_circuits_index');
    }

    #[Route('/suppression/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    public function deleteImage(Images $image, Request $request, EntityManagerInterface $em, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $nom = $image->getName();

            if($pictureService->delete($nom, 'circuits', 300, 300)){
                // On supprime l'image de la base de données
                $em->remove($image);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }
            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);
        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }

}
