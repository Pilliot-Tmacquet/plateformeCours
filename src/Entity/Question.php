<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionRepository::class)
 */
class Question
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
     * @ORM\ManyToOne(targetEntity=Questionnaire::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $id_questionnaire;

    /**
     * @ORM\ManyToOne(targetEntity=Proposition::class, inversedBy="id_question")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proposition;

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

    public function getIdQuestionnaire(): ?Questionnaire
    {
        return $this->id_questionnaire;
    }

    public function setIdQuestionnaire(?Questionnaire $id_questionnaire): self
    {
        $this->id_questionnaire = $id_questionnaire;

        return $this;
    }

    public function getProposition(): ?Proposition
    {
        return $this->proposition;
    }

    public function setProposition(?Proposition $proposition): self
    {
        $this->proposition = $proposition;

        return $this;
    }
}
