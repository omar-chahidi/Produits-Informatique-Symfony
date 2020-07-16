<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Produit;
use App\Form\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\File\UploadedFile;


class ImageController extends AbstractController
{
    /**
     * @Route("/image/{id}/telecharger", name="telecharger_image")
     */
    public function telechargerImage(Produit $produit, Request $request)
    {
        $image = new Image();

        // création du formulaire
        $formulaire = $this->createForm(ImageType::class, $image);

        // Analyse de la requette http
        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid()) {

            // Gestion du traitement de la photo après un téléchargement
            /**
             * @var UploadedFile $imagesTelecharger
             */
            $imagesTelecharger = $formulaire->get('chargementPhoto')->getData();

            if($imagesTelecharger) {

                // Traitement lorsque il y a un téléchargement multiple
                foreach ($imagesTelecharger as $uneImage) {
                    // On change le nom du fichier pour ne pas avoir de problème : même nom, des caractères spéciaux
                    $nouveauNomPhoto = md5(uniqid()) . '.' . $uneImage->guessExtension();

                    // Déplacement de l'upload dans son dossier de destination : repertoire stockage images
                    // dans le fichier config/services.yaml  on va mettre
                    //parameters:
                    //    repertoireStockageImages: '%kernel.project_dir%/public/ImageProduitsInformatique'
                    $uneImage->move(
                        $this->getParameter('repertoireStockageImages'),
                        $nouveauNomPhoto
                    );

                    // remplir l'objet image
                    $image->setProduit($produit);
                    $image->setNomImage($nouveauNomPhoto);
                    //dump($image);

                    // Persister l'mage
                    $entiteManager = $this->getDoctrine()->getManager();
                    $entiteManager->persist($image);
                    $entiteManager->flush();

                    // Redirection vers les information du produit : photos + variantes
                    return $this->redirectToRoute('ajouter_informations_produit', [
                        'id' => $produit->getId()
                    ]);

                } // Fin foreach


            } // Fin de if
        } // Fin de if du requette http

        return $this->render('image/telechargerImage.html.twig', [
            'produit' => $produit,
            'formulaireImage' => $formulaire->createView()
        ]);
    }
}
