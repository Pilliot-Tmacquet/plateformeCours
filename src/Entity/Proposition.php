<?php

namespace App\Entity;

use App\Repository\PropositionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropositionRepository::class)
 */
class Proposition
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
    private $reponse;

    /**
     * @ORM\Column(type="boolean")
     */
    private $propositionUser;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="proposition", orphanRemoval=true)
     */
    private $id_question;

    public function __construct()
    {
        $this->id_question = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }

    public function getPropositionUser(): ?bool
    {
        return $this->propositionUser;
    }

    public function setPropositionUser(bool $propositionUser): self
    {
        $this->propositionUser = $propositionUser;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getIdQuestion(): Collection
    {
        return $this->id_question;
    }

    public function addIdQuestion(Question $idQuestion): self
    {
        if (!$this->id_question->contains($idQuestion)) {
            $this->id_question[] = $idQuestion;
            $idQuestion->setProposition($this);
        }

        return $this;
    }

    public function removeIdQuestion(Question $idQuestion): self
    {
        if ($this->id_question->contains($idQuestion)) {
            $this->id_question->removeElement($idQuestion);
            // set the owning side to null (unless already changed)
            if ($idQuestion->getProposition() === $this) {
                $idQuestion->setProposition(null);
            }
        }

        return $this;
    }
}
