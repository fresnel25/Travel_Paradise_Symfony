<?php

namespace App\Entity;

use App\Repository\VisiteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VisiteurRepository::class)]
class Visiteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $addresse = null;

    /**
     * @var Collection<int, Associer>
     */
    #[ORM\OneToMany(targetEntity: Associer::class, mappedBy: 'visiteur')]
    private Collection $associers;

    public function __construct()
    {
        $this->associers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getAddresse(): ?string
    {
        return $this->addresse;
    }

    public function setAddresse(?string $addresse): static
    {
        $this->addresse = $addresse;

        return $this;
    }

    /**
     * @return Collection<int, Associer>
     */
    public function getAssociers(): Collection
    {
        return $this->associers;
    }

    public function addAssocier(Associer $associer): static
    {
        if (!$this->associers->contains($associer)) {
            $this->associers->add($associer);
            $associer->setVisiteur($this);
        }

        return $this;
    }

    public function removeAssocier(Associer $associer): static
    {
        if ($this->associers->removeElement($associer)) {
            // set the owning side to null (unless already changed)
            if ($associer->getVisiteur() === $this) {
                $associer->setVisiteur(null);
            }
        }

        return $this;
    }
}
