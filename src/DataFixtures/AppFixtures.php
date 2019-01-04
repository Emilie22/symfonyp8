<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Categorie;
use App\Entity\Article;
use App\Entity\Message;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	// attribut pour stocker l'encoder
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder) {
		// lors de l'instanciation, on stocke dans l'attribut encoder l'objet
		// qui va nous permettre d'encoder les mdp
		$this->encoder = $encoder;
	}

    public function load(ObjectManager $manager)
    {
        // je crée un tableau vide $users
        $users = [];
    	// on va créer 5 users
        for ($i = 1; $i <= 5; $i++) {
        	$user = new User();
        	$user->setUsername('Toto' . $i);
        	$user->setEmail('toto' . $i . '@toto.com');
        	if ($i === 1) { // je veux que toto1 ait le rôle admin
        		$roles = ['ROLE_USER', 'ROLE_ADMIN'];
        	}
        	else {
        		$roles = ['ROLE_USER'];
        	}
        	$user->setRoles($roles);
        	$plainPassword = 'toto';
        	$mdpencoded = $this->encoder->encodePassword($user, $plainPassword);
        	$user->setPassword($mdpencoded);

        	$manager->persist($user);

            // je remplis mon tableau $users à chaque tour de boucle
            $users[] = $user;
        }

        // je crée un tableau vide $categories
        $categories = [];
        // on va créer 10 catégories
        for ($i = 1; $i <= 10; $i++) {
        	$categorie = new Categorie();
        	$categorie->setLibelle('catégorie' . $i);
        	$categorie->setDescription('description' . $i);
        	$categorie->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));

        	$manager->persist($categorie);

            // je remplis mon tableau $categories à chaque tour de boucle
            $categories[] = $categorie;
        }

        // on va créer 50 articles
        for ($i = 1; $i <= 50; $i++) {
        	$article = new Article();
        	$article->setTitle('titre' . $i);
        	$article->setContent('contenu' . $i);
        	// on va générer les dates aléatoirement
        	$timestamp = mt_rand(1, time()); // time() temps en sec écoulé depuis le 01/01/1970
        	// formatage du timestamp en date
        	$randomDate = date('Y-m-d H:i:s', $timestamp);
        	$article->setDatePubli(new \DateTime($randomDate));
        	
        	// array_rand choisit au hasard une clé dans un tableau (le tableau $users ou $categories)
            $article->setUser($users[array_rand($users)]);
        	$article->setCategorie($categories[array_rand($categories)]);

        	$manager->persist($article);
        }

        // on va créer 20 messages
        for ($i = 1; $i <= 20; $i++) {
        	$message = new Message();
        	$message->setSujet('sujet' . $i);
        	$message->setContenu('contenu' . $i);
        	$message->setEmail('email@email.email' . $i);
        	$message->setDateenvoi(new \DateTime(date('Y-m-d H:i:s')));
        	$message->setNom('nom' . $i);

        	$manager->persist($message);
        }

        $manager->flush();
    }
}
