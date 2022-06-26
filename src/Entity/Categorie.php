<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
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
     * @ORM\OneToMany(targetEntity=FormModule::class, mappedBy="categorie", orphanRemoval=true,  cascade={"persist"})
     */
    private $formModules;

    public function __construct()
    {
        $this->formModules = new ArrayCollection();
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

    /**
     * @return Collection<int, FormModule>
     */
    public function getFormModules(): Collection
    {
        return $this->formModules;
    }

    public function addFormModule(FormModule $formModule): self
    {
        if (!$this->formModules->contains($formModule)) {
            $this->formModules[] = $formModule;
            $formModule->setCategorie($this);
        }

        return $this;
    }

    public function removeFormModule(FormModule $formModule): self
    {
        if ($this->formModules->removeElement($formModule)) {
            // set the owning side to null (unless already changed)
            if ($formModule->getCategorie() === $this) {
                $formModule->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->intitule;
    }
}
