<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleType;

class ArticleController extends AbstractController
{
    /**
     * route qui va afficher la liste des articles
     * @Route("/articles", name="articles")
     */
    public function index()
    {
    	// récupération de la liste des articles
    	$repository = $this->getDoctrine()->getRepository(Article::class);
    	$articles = $repository->myFindAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
    * @Route("/article/add", name="addArticle")
    */
    public function addArticle(Request $request) {

    	// pour pouvoir sauvegarder un objet = insérer les infos dans la table, 
    	// on utilise l'entity manager
    	$entityManager = $this->getDoctrine()->getManager();

    	// on crée notre objet article, pour l'instant en dur
    	$article = new Article();
    	/*$article->setTitle('mon premier article');
    	$article->setContent('zedfézhdfzdhezofyuozaeufz');
    	// on doit envoyer un objet de classe datetime puisqu'on a créé notre propriété 
    	// date_publi au format datetime
    	$article->setDatePubli(new \DateTime(date('Y-m-d H:i:s')));
    	$article->setAuthor('Moi');

    	// pour indiquer à doctrine de conserver l'objet, on doit le "persister"
    	$entityManager->persist($article);

    	// pour exécuter les requêtes sql
    	$entityManager->flush();

    	$this->addFlash('success', 'article ajouté');

    	return $this->render('article/add.html.twig');*/
    	
    	$form = $this->createForm(ArticleType::class);
    	$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()) {
    		$article = $form->getData();
    		$entityManager->persist($article);
    		$entityManager->flush();
    		$this->addFlash('success', 'article ajouté');
    		return $this->redirectToRoute('articles');
    	}
    	return $this->render('article/add.html.twig', ['form'=>$form->createView()]);
    }

 	// Créer une page qui va afficher les détails d'un article.
	// On utilise l'id de l'article pour récupérer l'article (placeholder dans l'url)

	/**
	* @Route("/article/article{id}", name="showArticle", requirements={"id"="\d+"})
	*/
	public function showArticle($id) {
		$repository = $this->getDoctrine()->getRepository(Article::class);
		$article = $repository->find($id);
		// génération d'une erreur si aucun article n'est trouvé
		if (!$article) {
			throw $this->createNotFoundException('No article found');
		}
		return $this->render('article/article.html.twig', array('article'=>$article));
	}


	/**
	* @Route("/article/recent", name="showRecentArticles")
	*/
	public function showRecent() {

		$repository = $this->getDoctrine()->getRepository(Article::class);
		// requête SQL : articles est un tableau de tableaux
		$articles = $repository->findAllPostedAfter('2000-01-01');
		// requête objet : articles2 est un tableau d'objets
		$articles2 = $repository->findAllPostedAfter2('2000-01-01');

		return $this->render('article/recent.html.twig', ['articles'=>$articles, 'articles2'=>$articles2]);
	}


	/**
	* @Route("article/update/{id}", name="updateArticle", requirements={"id"="\d+"})
	*/
	/*public function updateArticle($id) {
		$repository = $this->getDoctrine()->getRepository(Article::class);
		$article = $repository->find($id);
		if (!$article) {
			throw $this->createNotFoundException('No article found');
		}
		$article->setContent('contenu modifié');
		// récupération de l'entity manager pour pouvoir faire l'update
		$entityManager = $this->getDoctrine()->getManager();
		// pas besoin de faire ->persist($article) car l'article a été récupéré de la base,
		// doctrine le connait déjà
		$entityManager->flush();
		// création d'un message flash : stocké dans la session il sera supprimé dès qu'il sera affiché
		// donc affiché qu'une seule fois
		$this->addFlash('success', 'article modifié');
		// je redirige vers la page détail de l'article
		return $this->redirectToRoute('showArticle', ['id'=>$article->getId()]);		
	}*/
	public function updateArticle(Request $request, Article $article) {
	    if (!$article) {
			throw $this->createNotFoundException('No article found');
		}
		$entityManager = $this->getDoctrine()->getManager();
		$form = $this->createForm(ArticleType::class, $article);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$article = $form->getData();
			$entityManager->flush();
			$this->addFlash('success', 'article modifié');
			return $this->redirectToRoute('articles');
		}	
		return $this->render('article/add.html.twig', ['form'=>$form->createView()]);
	}


	/**
	* @Route("/article/delete/{id}", name="deleteArticle", requirements={"id"="\d+"})
	*/
	//public function deleteArticle($id) {
	//	$repository = $this->getDoctrine()->getRepository(Article::class);
	//	$article = $repository->find($id);
	//}
	// Le param converter : on explique à Symfony que l'on veut convertir directement l'id (dans l'url)
	// en objet de classe Article en mettant le nom de la classe dans les parenthèses
	// Il n'est plus utile de faire le getRepository/find
	public function deleteArticle(Article $article) {
		// récupération de l'entity manager, nécessaire pour la suppression
		$entityManager = $this->getDoctrine()->getManager();
		// je veux supprimer cet article
		$entityManager->remove($article);
		// pour valider la suppression
		$entityManager->flush();

		// génération d'un message flash
		$this->addFlash('warning', 'Article supprimé'); // ici c'est nous qui nommons warning
		// redirection vers la liste des articles
		return $this->redirectToRoute('articles');
	}
}
