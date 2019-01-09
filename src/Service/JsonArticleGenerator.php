<?php

namespace App\Service;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class JsonArticleGenerator {

	private $generator;

	public function __construct(UrlGeneratorInterface $generator) {
		$this->generator = $generator;
	}
 
	public function generateResult(Array $articles) {

		$result = [];
		foreach ($articles as $article) {
    		$result[] = ['title' => $article->getTitle(),
    					'date_publi' => $article->getDatePubli()->format('d/m/Y'),
    					'author' => $article->getUser()->getUsername(),
    					'content' => $article->getContent(),
    					'url' => $this->generator->generate('showArticle', ['id' => $article->getId()])
    					];
    	}
    	return $result;
	}

}