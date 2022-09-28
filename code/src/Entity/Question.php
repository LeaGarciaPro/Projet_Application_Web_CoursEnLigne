<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\ManyToOne(targetEntity=Questionnaire::class, inversedBy="questions")
     */
    private $Questionnaire;

    /**
     * @ORM\ManyToOne(targetEntity=QuestionType::class, inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $QuestionType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $question;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $suggestion;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $correctReponse;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="question")
     */
    private $reponses;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;

    }

    public function getQuestionnaire(): ?Questionnaire
    {
        return $this->Questionnaire;
    }

    public function setQuestionnaire(?Questionnaire $Questionnaire): self
    {
        $this->Questionnaire = $Questionnaire;

        return $this;
    }

    public function getQuestionType(): ?QuestionType
    {
        return $this->QuestionType;
    }

    public function setQuestionType(?QuestionType $QuestionType): self
    {
        $this->QuestionType = $QuestionType;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function __toString(){
        return $this->question;
    }
    public function getSuggestion(): ?string
    {
        return $this->suggestion;
    }

    public function setSuggestion($suggestion): self
    {
        $this->suggestion = $suggestion;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCorrectReponse()
    {
        return $this->correctReponse;
    }

    /**
     * @param mixed $correctReponse
     */
    public function setCorrectReponse($correctReponse): void
    {
        $this->correctReponse = $correctReponse;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }

        return $this;
    }
}
