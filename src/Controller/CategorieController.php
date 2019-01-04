<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\Request;
use App\Form\CategorieType;


class CategorieController extends AbstractController
{
    /**
     * @Route("/categories", name="categories")
     */
    public function index() {
    	// récupération du repository de la classe Categorie
    	$repository = $this->getDoctrine()->getRepository(Categorie::class);
    	// récupération des catégories
    	$categories = $repository->findAll();
    	// je passe les catégories en paramètre à ma vue
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }


    /**
    * @Route("/categorie/add", name="addCategorie")
    */
    public function addCategorie(Request $request) {
    	// $request contient toutes les informations sur la requête http,
    	// notamment get et post
    	// le manager va me permettre de persister mon entité
    	$entityManager = $this->getDoctrine()->getManager();
    	// je crée un objet Catégorie
    	$categorie = new Categorie();

    	// je le remplis en dur pour l'instant
    	/*$categorie->setLibelle('voyage');
    	$categorie->setDescription('jazkefazhjkfh');
    	$categorie->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
    	$entityManager->persist($categorie);
    	$entityManager->flush();
    	$this->addFlash('success', 'catégorie ajoutée');*/

    	// je crée un objet formulaire qui prend comme modèle l'entité Categorie
    	/*$form = $this->createFormBuilder($categorie)
    				->add('libelle', TextType::class)
    				->add('description', TextareaType::class)
    				->add('enregistrer', SubmitType::class)
    				->getForm();*/
    	$form = $this->createForm(CategorieType::class);


    	// je demande au formulaire de gérer la requête
    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		// le formulaire a été soumis et validé
    		// je crée un objet categorie à partir des données envoyées
    		// $form->getData() contient les données envoyées par l'utilisateur
    		$categorie = $form->getData();
    		// je rentre la date de création
    		$categorie->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));
    		// je persiste ma catégorie
    		$entityManager->persist($categorie);
    		$entityManager->flush();

    		$this->addFlash('success', 'catégorie ajoutée');
    		return $this->redirectToRoute('categories');
    	}
    	// je passe mon formulaire en paramètre de ma vue
    	return $this->render('categorie/add.html.twig', ['form'=>$form->createView()]);
    }


    /**
    * @Route("/categorie/categorie/{id}", name="showCategorie", requirements={"id"="\d+"})
    */
    public function showCategorie(Categorie $categorie) {
    	if (!$categorie) {
			throw $this->createNotFoundException('No categorie found');
		}
		return $this->render('categorie/categorie.html.twig', ['categorie'=>$categorie]);
    }


    /**
    * @Route("/categorie/recent", name="recentCategories")
    */
    public function getLastFive() {
    	$repository = $this->getDoctrine()->getRepository(Categorie::class);
    	$categories = $repository->getLastFive();
    	return $this->render('categorie/recent.html.twig', ['categories'=>$categories]);
    }


    /**
    * @Route("/categorie/update/{id}", name="updateCategorie", requirements={"id"="\d+"})
    */
    public function updateCategorie(Request $request, Categorie $categorie) {
    	if (!$categorie) {
			throw $this->createNotFoundException('No categorie found');
		}
		// récupération du manager
		$entityManager = $this->getDoctrine()->getManager();
		// je crée mon formulaire, je lui passe en second paramètre mon objet categorie afin
		// qu'il pré-remplisse le formulaire
		$form = $this->createForm(CategorieType::class, $categorie);
		// je lui donne la requête
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			// si le formulaire a été envoyé et validé
			// on récupère l'objet catégorie
			$categorie = $form->getData();
			// enregistrement dans la base
			$entityManager->flush();
			$this->addFlash('success', 'catégorie modifiée');
			return $this->redirectToRoute('categories');
		}
		return $this->render('categorie/add.html.twig', ['form'=>$form->createView()]);
    }


    /**
    * @Route("/categorie/delete/{id}", name="deleteCategorie", requirements={"id"="\d+"})
    */
    public function deleteCategorie(Categorie $categorie) {
    	$entityManager = $this->getDoctrine()->getManager();
		$entityManager->remove($categorie);
		$entityManager->flush();
		$this->addFlash('warning', 'catégorie supprimée');
		return $this->redirectToRoute('categories');
    }
}
