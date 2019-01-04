<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use App\Form\MessageType;

class MessageController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Message::class);
        $messages = $repository->findAll(); // tableau d'objets
        return $this->render('message/index.html.twig', ['messages'=>$messages]);
    }


    /**
    * @Route("/message/add", name="addMessage")
    */
    public function addMessage(Request $request) {
    	$message = new Message();
    	$form = $this->createForm(MessageType::class);
    	$form->handleRequest($request);
    	if ($form->isSubmitted() && $form->isValid()) {
    		$message = $form->getData();
    		$message->setDateEnvoi(new \DateTime(date('Y-m-d H:i:s')));
    		$entityManager = $this->getDoctrine()->getManager();
    		$entityManager->persist($message);
    		$entityManager->flush();
    		$this->addFlash('success', 'message envoyé');
    		return $this->redirectToRoute('showMessage', ['id'=>$message->getId()]);
    	}
    	return $this->render('message/add.html.twig', ['form'=>$form->createView()]);
    }


    /**
    * @Route("/message/{id}", name="showMessage", requirements={"id"="\d+"})
    */
    public function showMessage(Message $message) {
    	return $this->render('message/message.html.twig', ['message'=>$message]);
    }
}
