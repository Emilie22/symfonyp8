<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index()
    {
    	$repository = $this->getDoctrine()->getRepository(User::class);
    	$users = $repository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
    * @Route("user/user/{id}", name="showUser", requirements={"id"="\d+"})
    */
    public function showUser(User $user) {
    	return $this->render('user/user.html.twig', ['user'=>$user]);
    }
}
