<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;


// denormalizationContext={"groups":{"interventions:write"}})

/**
 * @ORM\Entity(repositoryClass="App\Repository\InterventionRepository")
 * @ApiResource(
 *  normalizationContext={"groups":{"interventions:read"}})
 *  
 */
class Intervention
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"interventions:read", "cares:read", "images:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"interventions:read", "cares:read", "interventions:write"})
     * 
     */
    private $date;

    /**
     * @ORM\Column(type="text")
     * @Groups({"interventions:read", "cares:read", "interventions:write"})
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="intervention", orphanRemoval=true)
     * @Groups({"interventions:read", "cares:read", "interventions:write"})
     */
    private $images;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Care", inversedBy="interventions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"interventions:read", "images:read"})
     */
    private $care;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setIntervention($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getIntervention() === $this) {
                $image->setIntervention(null);
            }
        }

        return $this;
    }

    /**
     * 
     *
     * @return Care|null
     */
    public function getCare(): ?Care
    {
        return $this->care;
    }

    public function setCare(?Care $care): self
    {
        $this->care = $care;

        return $this;
    }
}
