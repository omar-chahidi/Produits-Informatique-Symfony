<?php

namespace App\Controller;

use App\Entity\Produit;
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
        // exemple 0
        $repo = $this->getDoctrine()->getRepository(Produit::class);

        // exemple 1
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
}