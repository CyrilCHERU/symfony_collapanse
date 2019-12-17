<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CareRepository")
 * @ApiResource(
 *  normalizationContext={"groups":{"cares:read"}}
 * )
 */
class Care
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"cares:read", "patients:read", "interventions:read", "images:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"cares:read", "patients:read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"cares:read", "patients:read"})
     */
    private $endedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"cares:read", "patients:read", "interventions:read"})
     */
    private $woundType;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervention", mappedBy="care", orphanRemoval=true)
     * @ORM\OrderBy({"date"="DESC"})
     * @Groups({"cares:read", "patients:read"})
     */
    private $interventions;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="cares")
     * @Groups({"cares:read", "interventions:read"})
     * 
     */
    private $patient;

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getEndedAt(): ?\DateTimeInterface
    {
        return $this->endedAt;
    }

    public function setEndedAt(?\DateTimeInterface $endedAt): self
    {
        $this->endedAt = $endedAt;

        return $this;
    }

    public function getWoundType(): ?string
    {
        return $this->woundType;
    }

    public function setWoundType(string $woundType): self
    {
        $this->woundType = $woundType;

        return $this;
    }

    /**
     * @return Collection|Intervention[]
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions[] = $intervention;
            $intervention->setCare($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->contains($intervention)) {
            $this->interventions->removeElement($intervention);
            // set the owning side to null (unless already changed)
            if ($intervention->getCare() === $this) {
                $intervention->setCare(null);
            }
        }

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }
}
