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
}
