<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Article;

class AjaxController extends AbstractController
{
    /**
     * @Route("/ajax/auteur/", name="auteur1")
     */
    public function index(Request $request)
    {
    	$idAuteur = $request->request->get('idAuteur', null);
    	
    	if (empty($idAuteur) || !preg_match("#^\d+$#", $idAuteur)) {
    		return new Response('paramètre invalide');
    	}

    	$user = $this->getDoctrine()->getRepository(User::class)->findById($idAuteur);

    	$articles = $this->getDoctrine()->getRepository(Article::class)->findByUser($user);

        return $this->render('ajax/articles.html.twig', ['articles'=>$articles]);
    }

    /**
    * @Route("/ajax/auteur2/{id}", name="auteur2", requirements={"id"="\d+"})
    */
    public function auteur2(User $user) {
    	$articles = $this->getDoctrine()->getRepository(Article::class)->findByUser($user);
    	$result = [];
    	foreach ($articles as $article) {
    		$result[] = ['title' => $article->getTitle(),
    					'date_publi' => $article->getDatePubli()->format('d/m/Y'),
    					'author' => $article->getUser()->getUsername(),
    					'content' => $article->getContent(),
    					'url' => $this->generateUrl('showArticle', ['id' => $article->getId()])
    					];
    	}
    	// renvoi d'une réponse au format json
    	return $this->json(['status' => 'ok', 'articles' => $result]);
    }
}
