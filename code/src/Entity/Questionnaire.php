<?php

namespace App\Entity;

use App\Repository\QuestionnaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuestionnaireRepository::class)
 */
class Questionnaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class, inversedBy="questionnaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Matiere;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $Titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Description;

    /**
     * @ORM\OneToMany(targetEntity=Question::class, mappedBy="Questionnaire", cascade={"persist"})
     */
    private $questions;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="questionnaires")
     */
    private $utilisateur;


    /**
     * @ORM\OneToMany(targetEntity=QuestionnaireEvaluation::class, mappedBy="questionnaire")
     */
    private $questionnaireEvaluation;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->reponses = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
        $this->questionnaireEvaluation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->Matiere;
    }

    public function setMatiere(?Matiere $Matiere): self
    {
        $this->Matiere = $Matiere;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): self
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuestionnaire($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuestionnaire() === $this) {
                $question->setQuestionnaire(null);
            }
        }

        return $this;
    }

    public function addQuestions(Question $question): void
    {
        $question->setQuestionnaire($this);
        $this->questions->add($question);
    }

    public function removeQuestions(Question $question): void
    {
        $this->questions->removeElement($question);
    }


    public function __tostring()
    {
        return $this->Titre."-".$this->Description;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }


    /**
     * @return Collection|QuestionnaireEvaluation[]
     */
    public function getQuestionnaireEvaluation(): Collection
    {
        return $this->questionnaireEvaluation;
    }

    public function addQuestionnaireEvaluation(QuestionnaireEvaluation $questionnaireEvaluation): self
    {
        if (!$this->questionnaireEvaluation->contains($questionnaireEvaluation)) {
            $this->questionnaireEvaluation[] = $questionnaireEvaluation;
            $questionnaireEvaluation->setQuestionnaire($this);
        }

        return $this;
    }

    public function removeQuestionnaireEvaluation(QuestionnaireEvaluation $questionnaireEvaluation): self
    {
        if ($this->questionnaireEvaluation->removeElement($questionnaireEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($questionnaireEvaluation->getQuestionnaire() === $this) {
                $questionnaireEvaluation->setQuestionnaire(null);
            }
        }

        return $this;
    }

}
