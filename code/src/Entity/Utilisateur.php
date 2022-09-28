<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UtilisateurRepository::class)
 */
class Utilisateur implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="utilisateur")
     */
    private $documents;

    /**
     * @ORM\OneToMany(targetEntity=Questionnaire::class, mappedBy="utilisateur")
     */
    private $questionnaires;

    /**
     * @ORM\OneToOne(targetEntity=Etudiant::class, mappedBy="utilisateur", cascade={"persist", "remove"})
     */
    private $etudiant;

    /**
     * @ORM\OneToOne(targetEntity=Enseignant::class, mappedBy="utilisateur", cascade={"persist", "remove"})
     */
    private $enseignant;

    /**
     * @ORM\OneToOne(targetEntity=Parents::class, mappedBy="utilisateur", cascade={"persist", "remove"})
     */
    private $parents;

    /**
     * @ORM\OneToMany(targetEntity=Etudiant::class, mappedBy="parent")
     */
    private $etudiants;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="utilisateur")
     */
    private $reponses;

    /**
     * @ORM\OneToMany(targetEntity=QuestionnaireEvaluation::class, mappedBy="utilisateur")
     */
    private $questionnaireEvaluation;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="utilisateur")
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity=Pdf::class, mappedBy="utilisateur")
     */
    private $pdfs;

    /**
     * @ORM\OneToOne(targetEntity=Admin::class, mappedBy="utilisateur", cascade={"persist", "remove"})
     */
    private $admin;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
        $this->questionnaires = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
        $this->reponses = new ArrayCollection();
        $this->questionnaireEvaluation = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->pdfs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }
    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setUtilisateur($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getUtilisateur() === $this) {
                $document->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Questionnaire[]
     */
    public function getQuestionnaires(): Collection
    {
        return $this->questionnaires;
    }

    public function addQuestionnaire(Questionnaire $questionnaire): self
    {
        if (!$this->questionnaires->contains($questionnaire)) {
            $this->questionnaires[] = $questionnaire;
            $questionnaire->setUtilisateur($this);
        }

        return $this;
    }

    public function removeQuestionnaire(Questionnaire $questionnaire): self
    {
        if ($this->questionnaires->removeElement($questionnaire)) {
            // set the owning side to null (unless already changed)
            if ($questionnaire->getUtilisateur() === $this) {
                $questionnaire->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        // unset the owning side of the relation if necessary
        if ($etudiant === null && $this->etudiant !== null) {
            $this->etudiant->setUtilisateur(null);
        }

        // set the owning side of the relation if necessary
        if ($etudiant !== null && $etudiant->getUtilisateur() !== $this) {
            $etudiant->setUtilisateur($this);
        }

        $this->etudiant = $etudiant;

        return $this;
    }

    public function __toString(){
        return $this->getUsername();
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): self
    {
        // unset the owning side of the relation if necessary
        if ($enseignant === null && $this->enseignant !== null) {
            $this->enseignant->setUtilisateur(null);
        }

        // set the owning side of the relation if necessary
        if ($enseignant !== null && $enseignant->getUtilisateur() !== $this) {
            $enseignant->setUtilisateur($this);
        }

        $this->enseignant = $enseignant;

        return $this;
    }

    public function getParents(): ?Parents
    {
        return $this->parents;
    }

    public function setParents(?Parents $parents): self
    {
        // unset the owning side of the relation if necessary
        if ($parents === null && $this->parents !== null) {
            $this->parents->setUtilisateur(null);
        }

        // set the owning side of the relation if necessary
        if ($parents !== null && $parents->getUtilisateur() !== $this) {
            $parents->setUtilisateur($this);
        }

        $this->parents = $parents;

        return $this;
    }

    /**
     * @return Collection|Etudiant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->setParent($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getParent() === $this) {
                $etudiant->setParent(null);
            }
        }

        return $this;
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
            $reponse->setUtilisateur($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getUtilisateur() === $this) {
                $reponse->setUtilisateur(null);
            }
        }

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
            $questionnaireEvaluation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeQuestionnaireEvaluation(QuestionnaireEvaluation $questionnaireEvaluation): self
    {
        if ($this->questionnaireEvaluation->removeElement($questionnaireEvaluation)) {
            // set the owning side to null (unless already changed)
            if ($questionnaireEvaluation->getUtilisateur() === $this) {
                $questionnaireEvaluation->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setUtilisateur($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getUtilisateur() === $this) {
                $video->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Pdf[]
     */
    public function getPdfs(): Collection
    {
        return $this->pdfs;
    }

    public function addPdf(Pdf $pdf): self
    {
        if (!$this->pdfs->contains($pdf)) {
            $this->pdfs[] = $pdf;
            $pdf->setUtilisateur($this);
        }

        return $this;
    }

    public function removePdf(Pdf $pdf): self
    {
        if ($this->pdfs->removeElement($pdf)) {
            // set the owning side to null (unless already changed)
            if ($pdf->getUtilisateur() === $this) {
                $pdf->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): self
    {
        // unset the owning side of the relation if necessary
        if ($admin === null && $this->admin !== null) {
            $this->admin->setUtilisateur(null);
        }

        // set the owning side of the relation if necessary
        if ($admin !== null && $admin->getUtilisateur() !== $this) {
            $admin->setUtilisateur($this);
        }

        $this->admin = $admin;

        return $this;
    }
}
