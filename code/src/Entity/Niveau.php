<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
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
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Etudiant::class, mappedBy="niveau")
     */
    private $etudiants;

    /**
     * @ORM\ManyToMany(targetEntity=NiveauFiliere::class, inversedBy="niveaux")
     */
    private $niveaufiliere;



    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->niveaufiliere = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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
            $etudiant->setNiveau($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getNiveau() === $this) {
                $etudiant->setNiveau(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NiveauFiliere[]
     */
    public function getNiveaufiliere(): Collection
    {
        return $this->niveaufiliere;
    }

    public function addNiveaufiliere(NiveauFiliere $niveaufiliere): self
    {
        if (!$this->niveaufiliere->contains($niveaufiliere)) {
            $this->niveaufiliere[] = $niveaufiliere;
        }

        return $this;
    }

    public function removeNiveaufiliere(NiveauFiliere $niveaufiliere): self
    {
        $this->niveaufiliere->removeElement($niveaufiliere);

        return $this;
    }

    public function __toString(){
        return $this->nom;
    }
}
