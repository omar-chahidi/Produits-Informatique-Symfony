<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Variante;
use App\Form\VarianteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VarianteController extends AbstractController
{
    /**
     * @Route("/variante", name="variante")
     */
    public function index()
    {
        return $this->render('variante/index.html.twig', [
            'controller_name' => 'VarianteController',
        ]);
    }

    /**
     * php bin/console make:form VarianteType
     * @Route("/variante/{id}/ajouter", name="ajouter_variante")
     */
    public function ajouterVariante(Produit $produit, Request $request) {

        dump($produit);
        dump($produit->getCategorie()->getNomCategorie());

        $variante = new Variante();
        dump($variante);

        /**/
        if( $produit->getCategorie()->getNomCategorie() == 'Smartphones' or $produit->getCategorie()->getNomCategorie() == 'Tablettes'  ) {
            $variante->setCore('null');
        }elseif ( $produit->getCategorie()->getNomCategorie() == 'Accessoires' or $produit->getCategorie()->getNomCategorie() == 'Montres connectées'){
            $variante->setMemoire('null');
            $variante->setCore('null');
            $variante->setEspaceDisque('null');
        }


        // Création du formulaire
        $formulaire = $this->createForm(VarianteType::class, $variante);

        // Analyse de la requette http
        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid()) {
            $variante->setProduit($produit);
            dump($variante);

            $entiteManager = $this->getDoctrine()->getManager();
            $entiteManager->persist($variante);
            $entiteManager->flush();

            return $this->redirectToRoute('ajouter_informations_produit', [
                'id' => $produit->getId()
            ]);
        }


        return $this->render('variante/ajouterModifierVariante.html.twig', [
            'formulaireVariante' => $formulaire->createView(),
            'produit' => $produit,
            'mode' => 'ajouterVariante',
            'categorie' => $produit->getCategorie()->getNomCategorie()
        ]);
    }


    /**
     * php bin/console make:form VarianteType
     * @Route("/variante/{id}/modifier", name="modifier_variante")
     */
    public function modifierVariante(Variante $variante, Request $request) {
        dump($variante);

        // Création du formulaire
        $formulaire = $this->createForm(VarianteType::class, $variante);

        // Analyse de la requette http
        $formulaire->handleRequest($request);
        if($formulaire->isSubmitted() && $formulaire->isValid()) {
            $entiteManager = $this->getDoctrine()->getManager();
            $entiteManager->persist($variante);
            $entiteManager->flush();

            return $this->redirectToRoute('ajouter_informations_produit', [
                'id' => $variante->getProduit()->getId()
            ]);
        }


        return $this->render('variante/ajouterModifierVariante.html.twig', [
            'formulaireVariante' => $formulaire->createView(),
            'produit' => $variante->getProduit(),
            'mode' => 'modifierVariante',
            'categorie' => $variante->getProduit()->getCategorie()->getNomCategorie()
        ]);
    }

    /**
     * @Route("/variante/{id}/supprimer", name="supprimer_variante")
     */
    public function supprimerVariante(Variante $variante) {
        $entitemanager = $this->getDoctrine()->getManager();
        $entitemanager->remove($variante);
        $entitemanager->flush();

        return $this->redirectToRoute('ajouter_informations_produit', [
            'id' => $variante->getProduit()->getId()
        ]);
    }

}
