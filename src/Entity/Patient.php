<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *  normalizationContext={"groups":{"patients:read"}}
 * )
 */
class Patient
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"patients:read", "cares:read", "interventions:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis.")
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis.")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Ce champ doit comporter au minimum {{ limit }} caractères.")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis.")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Ce champ doit comporter au minimum {{ limit }} caractères.")
     */
    private $lastName;

    /**
     * @ORM\Column(type="date")
     * @Groups({"patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis.")
     * @Assert\Date
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis.")
     * @Assert\Length(
     *      min = 3,
     *      minMessage = "Ce champ doit comporter au minimum {{ limit }} caractères.")
     */
    private $address1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"patients:read"})
     */
    private $address2;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis.")
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      exactMessage = "Ce champ ne doit comporter que {{ limit }} caractères.")
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis.")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis.")
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      exactMessage = "Ce champ ne doit comporter que {{ limit }} caractères.")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"patients:read"})
     * @Assert\Email(message = "Cet email '{{ value }}' n'est pas un email valide.")
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="patients")
     * 
     * @Groups({"patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis.")
     */
    private $doctor;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="careGiver")
     * @Groups({"patients:read"})
     */
    private $nurses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Care", mappedBy="patient")
     * @Groups({"patients:read"})
     * @ORM\OrderBy({"createdAt"="DESC"})
     * @ApiSubresource
     * 
     */
    private $cares;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"patients:read"})
     */
    private $slug;

    public function __construct()
    {
        $this->nurses = new ArrayCollection();
        $this->cares = new ArrayCollection();
    }

    /**
     * Permet d'initialiser le slug
     * 
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @return void
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {

            $this->slug = strtoupper($this->lastName) . '-' . $this->firstName;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(?string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDoctor(): ?User
    {
        return $this->doctor;
    }

    public function setDoctor(?User $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getNurses(): Collection
    {
        return $this->nurses;
    }

    public function addNurse(User $nurse): self
    {
        if (!$this->nurses->contains($nurse)) {
            $this->nurses[] = $nurse;
        }

        return $this;
    }

    public function removeNurse(User $nurse): self
    {
        if ($this->nurses->contains($nurse)) {
            $this->nurses->removeElement($nurse);
        }

        return $this;
    }

    /**
     * @Groups({"patients:read"})
     * 
     * @return Collection|Care[]
     */
    public function getCares(): Collection
    {
        return $this->cares;
    }

    public function addCare(Care $care): self
    {
        if (!$this->cares->contains($care)) {
            $this->cares[] = $care;
            $care->setPatient($this);
        }

        return $this;
    }

    public function removeCare(Care $care): self
    {
        if ($this->cares->contains($care)) {
            $this->cares->removeElement($care);
            // set the owning side to null (unless already changed)
            if ($care->getPatient() === $this) {
                $care->setPatient(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @Groups({"patients:read", "cares:read", "interventions:read"})
     *
     * @return void
     */
    public function getFullName()
    {
        return strtoupper($this->getLastName()) . " " . $this->getFirstName();
    }
}
