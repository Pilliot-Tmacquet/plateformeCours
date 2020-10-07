<?php

namespace App\Entity;

use App\Repository\ChapitresRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ChapitresRepository::class)
 */
class Chapitres
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=cours::class, inversedBy="chapitres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_cour;

    /**
     * @ORM\OneToOne(targetEntity=Questionnaire::class, mappedBy="id_question", cascade={"persist", "remove"})
     */
    private $questionnaire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getIdCour(): ?cours
    {
        return $this->id_cour;
    }

    public function setIdCour(?cours $id_cour): self
    {
        $this->id_cour = $id_cour;

        return $this;
    }

    public function getQuestionnaire(): ?Questionnaire
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire(Questionnaire $questionnaire): self
    {
        $this->questionnaire = $questionnaire;

        // set the owning side of the relation if necessary
        if ($questionnaire->getIdChapitre() !== $this) {
            $questionnaire->setIdChapitre($this);
        }

        return $this;
    }
}
