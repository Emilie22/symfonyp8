<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AdminArticleType;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\File\File;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
    * @Route("/test/deny")
    * Pour contrôler plus finement l'accès à nos contrôleurs
    */
    public function testDeny() {
    	// si l'utilisateur n'a pas le ROLE_AUTEUR, une erreur 403 est renvoyée
    	$this->denyAccessUnlessGranted('ROLE_AUTEUR', null, 'page interdite !');
    	// si on a le ROLE_AUTEUR, le reste du controleur est exécuté
    	return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
    * Autre méthode pour restreindre l'accès à un contrôleur : les annotations
    * @Route("/test/deny2")
    * @Security("has_role('ROLE_AUTEUR')")
    */
    public function testDeny2() {
    	return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
    * @Route("/admin/article/add", name="addArticleAdmin")
    */
    public function addArticleAdmin(Request $request) {
        $form = $this->createForm(AdminArticleType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $file = $article->getImage();
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->getParameter('article_image_directory'), $filename);
            $article->setImage($filename);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'article ajouté');
            return $this->redirectToRoute('admin');
        }
        return $this->render('admin/add_article.html.twig', ['form'=>$form->createView()]);
    }

    /**
    * @Route("/admin/article/update/{id}", name="updateArticleAdmin", requirements={"id"="\d+"})
    */
    public function updateArticleAdmin(Request $request, Article $article) {
        $filename = $article->getImage();
        if ($article->getImage()) {
            $article->setImage(new File($this->getParameter('article_image_directory') . '/' . $filename));
        }
        $form = $this->createForm(AdminArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            if ($article->getImage()) {
                $file = $article->getImage();
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('article_image_directory'), $filename);
            }
            $article->setImage($filename);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('success', 'article modifié');
            return $this->redirectToRoute('articles');
        }   
        return $this->render('admin/update_article.html.twig', ['form'=>$form->createView()]);
    }
}
