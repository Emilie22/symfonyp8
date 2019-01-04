<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategorieRepository")
 */
class Categorie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Length(
     *          min = 3,
     *          max = 30,
     *          minMessage = "La catégorie doit faire au moins 3 caractères",
     *          maxMessage = "La catégorie ne doit pas dépasser 30 caractères"
     *          )
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_creation;

    /**
    * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="categorie", orphanRemoval=true)
    */
    private $articles;

    public function __construct() {
        // on initialise la propriété articles lors de l'instanciation
        // ArrayCollection se comporte comme un tableau
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    // getter pour notre propriété articles
    public function getArticles(): Collection {
        return $this->articles;
    }
}
