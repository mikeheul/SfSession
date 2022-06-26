<?php

namespace App\Entity;

use ArrayIterator;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use App\Repository\SessionRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SessionRepository::class)
 * @ApiResource()
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $intitule;

    /**
     * @ORM\Column(type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbPlaces;

    /**
     * @ORM\ManyToOne(targetEntity=Formateur::class, inversedBy="sessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formateurReferent;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class, inversedBy="sessions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $formation;

    /**
     * @ORM\OneToMany(targetEntity=Programme::class, mappedBy="session", orphanRemoval=true)
     * 
     */
    private $programmes;

    /**
     * @ORM\ManyToMany(targetEntity=Stagiaire::class, inversedBy="sessions", cascade={"persist"})
     */
    private $stagiaires;

    public function __construct()
    {
        $this->programmes = new ArrayCollection();
        $this->stagiaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getNbPlaces(): ?int
    {
        return $this->nbPlaces;
    }

    public function setNbPlaces(int $nbPlaces): self
    {
        $this->nbPlaces = $nbPlaces;

        return $this;
    }

    public function getFormateurReferent(): ?Formateur
    {
        return $this->formateurReferent;
    }

    public function setFormateurReferent(?Formateur $formateurReferent): self
    {
        $this->formateurReferent = $formateurReferent;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        // $iterator = $this->programmes->getIterator();
        // $iterator->uasort(function ($a, $b) {
        //     return ($a->getFormModule()->getCategorie()->getIntitule() < $b->getFormModule()->getCategorie()->getIntitule()) ? -1 : 1;
        // });
        // $collection = new ArrayCollection(iterator_to_array($iterator));
        // return $collection;
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes[] = $programme;
            $programme->setSession($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): self
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getSession() === $this) {
                $programme->setSession(null);
            }
        }

        return $this;
    }

    public function getTotalJours() {
        $total = 0;
        foreach($this->programmes as $prog) {
            $total += $prog->getNbJours();
        }
        return $total;
    }

    /**
     * @return Collection<int, Stagiaire>
     */
    public function getStagiaires(): Collection
    {
        return $this->stagiaires;
    }

    public function addStagiaire(Stagiaire $stagiaire): self
    {
        if (!$this->stagiaires->contains($stagiaire)) {
            $this->stagiaires[] = $stagiaire;
        }

        return $this;
    }

    public function removeStagiaire(Stagiaire $stagiaire): self
    {
        $this->stagiaires->removeElement($stagiaire);

        return $this;
    }

    public function getStatut() {
        $statut = (count($this->stagiaires) == $this->nbPlaces) ? "Session complète" : "";
        return mb_strtoupper($statut);
    }

    public function __toString()
    {
        return $this->intitule;
    }
}
