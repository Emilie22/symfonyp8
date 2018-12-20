<?php

// je range toutes mes classes de controleurs dans le namespace App\Controller
namespace App\Controller; // App est le dossier en cours, ici App = le dossier SRC

use Symfony\Component\HttpFoundation\Response;
// pour pouvoir utiliser les annotations :
use Symfony\Component\Routing\Annotation\Route;

class HomeController {

	// déclaration de notre méthode / controleur

	/**
	* grâce aux annotations, je peux déclarer ma route
	* @Route("/bonjour")
	*
	*/

	public function bonjour() {
		return new Response('<html><body><strong>Bonjour</strong></body></html>');
	}

	// Exercice : créer une page pour l'url /exercice1/comment-allez-vous, qui affiche "bien, merci"

	/**
	* @Route("/exercice1/comment-allez-vous")
	*
	*/	

	public function commentAllezVous() {
		return new Response('<html><body><strong>Bien, merci</strong></body></html>');
	}

}