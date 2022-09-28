<?php

namespace App\Entity;

use App\Repository\ContenuRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContenuRepository::class)
 */
class Contenu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenuJSON;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenuJSON()
    {

        return json_decode($this->contenuJSON);
    }

    public function setContenuJSON(string $contenuJSON): self
    {
        $this->contenuJSON = $contenuJSON;

        return $this;
    }
}
