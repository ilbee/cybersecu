<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PersonalityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PersonalityRepository::class)
 * @ApiResource()
 */
class Personality
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
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=Movie::class, mappedBy="actor")
     */
    private $moviesActor;

    public function __construct()
    {
        $this->moviesActor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMoviesActor(): Collection
    {
        return $this->moviesActor;
    }

    public function addMoviesActor(Movie $moviesActor): self
    {
        if (!$this->moviesActor->contains($moviesActor)) {
            $this->moviesActor[] = $moviesActor;
            $moviesActor->addActor($this);
        }

        return $this;
    }

    public function removeMoviesActor(Movie $moviesActor): self
    {
        if ($this->moviesActor->removeElement($moviesActor)) {
            $moviesActor->removeActor($this);
        }

        return $this;
    }
}
