<?php

namespace App\Entity;

use App\Repository\AssocierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssocierRepository::class)]
class Associer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $presence = null;

    #[ORM\ManyToOne(inversedBy: 'associers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Visit $visite = null;

    #[ORM\ManyToOne(inversedBy: 'associers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Visiteur $visiteur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isPresence(): ?bool
    {
        return $this->presence;
    }

    public function setPresence(bool $presence): static
    {
        $this->presence = $presence;

        return $this;
    }

    public function getVisite(): ?Visit
    {
        return $this->visite;
    }

    public function setVisite(?Visit $visite): static
    {
        $this->visite = $visite;

        return $this;
    }

    public function getVisiteur(): ?Visiteur
    {
        return $this->visiteur;
    }

    public function setVisiteur(?Visiteur $visiteur): static
    {
        $this->visiteur = $visiteur;

        return $this;
    }
}
