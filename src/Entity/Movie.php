<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MovieRepository::class)
 * @ApiResource()
 */
class Movie
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $overview;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $releasedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Personality::class, inversedBy="moviesActor")
     */
    private $actor;

    public function __construct()
    {
        $this->actor = new ArrayCollection();
        $this->director = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->tags;
    }

    public function setTags(?string $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(?string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getReleasedAt(): ?\DateTimeImmutable
    {
        return $this->releasedAt;
    }

    public function setReleasedAt(\DateTimeImmutable $releasedAt): self
    {
        $this->releasedAt = $releasedAt;

        return $this;
    }

    /**
     * @return Collection|Personality[]
     */
    public function getActor(): Collection
    {
        return $this->actor;
    }

    public function addActor(Personality $actor): self
    {
        if (!$this->actor->contains($actor)) {
            $this->actor[] = $actor;
        }

        return $this;
    }

    public function removeActor(Personality $actor): self
    {
        $this->actor->removeElement($actor);

        return $this;
    }

    /**
     * @return Collection|Personality[]
     */
    public function getDirector(): Collection
    {
        return $this->director;
    }

    public function addDirector(Personality $director): self
    {
        if (!$this->director->contains($director)) {
            $this->director[] = $director;
        }

        return $this;
    }

    public function removeDirector(Personality $director): self
    {
        $this->director->removeElement($director);

        return $this;
    }
}
