<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Produit;
use App\Entity\Variante;
use App\Form\ProduitType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function home(Request $request){
        dump($request);
        return $this->render('produit/home.html.twig', [
            'titre' => 'Je suis la page home'
        ]);
    }

    /**
     * @Route("/produit/ListeProduits/{categorie}", name="liste_produits_par_categorie")
     */
    public function index($categorie)
    {
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        //$articles = $repo->findAll();

        $articles = $repo->listeProduitsPourChaqueCategorie($categorie);
        dump($articles);
        /*
        foreach ($articles as $unArticle) {
            var_dump($unArticle->getNomProduit());
            foreach ($unArticle->getVariantes() as $variante){
                var_dump($variante->getMaster());
                var_dump($variante->getPrix());
            }
            foreach ($unArticle->getImages() as $photo){
                var_dump($photo->getMaster());
                var_dump($photo->getNomImage());
            }
        }
        */

        return $this->render('produit/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     *@Route("/produit/{id}", name="afficher_un_produit", requirements={"id"="\d+"})
     */
    public function afficherUnProduit($id){
        // Trouver le produit séléctionner
        $repo = $this->getDoctrine()->getRepository(Produit::class);
        $unArticle = $repo->find($id);
        //dd($unArticle);

        // Les images de mon produit
        $repoImage = $this->getDoctrine()->getRepository(Image::class);
        $imagesProduit = $repoImage->findBy(array('produit' => $unArticle));
        //dd($imagesProduit);

        // Liste des variantes pour chaque produit
        $repVariante = $this->getDoctrine()->getRepository(Variante::class);
        $varianteProduit = $repVariante->findBy(array('produit' => $unArticle));
        // dd($varianteProduit);

        return $this->render('produit/afficherUnProduit.html.twig', [
            'unArticle' => $unArticle,
            'imagesProduit' => $imagesProduit,
            'varianteProduit' => $varianteProduit
        ]);
    }

    /**
     * @Route("/produit/ajouter", name="ajouter_produit")
     * php bin/console make:form ProduitType
     */
    public function formulaire(Request $request) {

        $produit = new Produit();

        // Création du formulaire
        $formulaire = $this->createForm(ProduitType::class, $produit);

        // Annalyser la requette http
        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid()) {
            dump($produit);
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
        }

        return $this->render('produit/ajouterProduit.html.twig', [
            'formulaireProduit' => $formulaire->createView()
        ]);
    }
}
