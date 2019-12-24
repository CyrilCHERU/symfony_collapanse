<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;


use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity("email")
 * @ApiResource(normalizationContext={"groups":{"users:read"}})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"users:read", "users:login", "patients:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"users:read"})
     * @Assert\NotBlank(message="Ce champ est requis !")
     * @Assert\Email(message = "Cet email '{{ value }}' n'est pas un email valide.")
     * @Groups({"users:read", "users:login"})
     * 
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"users:login"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Ce champ est requis !")
     * @Assert\Length(
     *  min = 8, 
     *  minMessage = "Votre mot de passe doit comporter au minimum {{ limit }} caractères.")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"users:read", "users:login"})
     * @Assert\NotBlank(message="Ce champ est requis !")
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read", "patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis !")
     * @Assert\Length(min = 3, minMessage = "Ce champs doit comporter au minimum {{ limit }} caractères.")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read", "patients:read"})
     * @Assert\NotBlank(message="Ce champ est requis !")
     * @Assert\Length(min = 3, minMessage = "Ce champs doit comporter au minimum {{ limit }} caractères.")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=10)
     * @Groups({"users:read"})
     * @Assert\NotBlank(message="Ce champ est requis !")
     * @Assert\Length(
     *      min = 10,
     *      max = 10,
     *      exactMessage = "Le numéro de téléphone ne doit comporter que {{ limit }} caractères.")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=9)
     * @Groups({"users:read"})
     * @Assert\NotBlank(message="Ce champ est requis !")
     * @Assert\Length(
     *      min = 9,
     *      max = 9,
     *      exactMessage = "Le numéro ADELI ne doit comporter que {{ limit }} caractères.")
     */
    private $adeli;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read"})
     * @Assert\NotBlank(message="Ce champ est requis !")
     */
    private $address1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"users:read"})
     */
    private $address2;

    /**
     * @ORM\Column(type="string", length=5)
     * @Groups({"users:read"})
     * @Assert\NotBlank(message="Ce champ est requis !")
     * @Assert\Length(
     *      min = 5,
     *      max = 5,
     *      exactMessage = "Le code postal ne doit comporter que {{ limit }} caractères.")
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read"})
     * @Assert\NotBlank(message="Ce champ est requis !")
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users:read"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Patient", mappedBy="doctor")
     * 
     * 
     */
    private $patients;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Patient", mappedBy="nurses")
     */
    private $careGiver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Job", inversedBy="user")
     * @Assert\NotBlank(message="Ce champ est requis !")
     * @Groups({"users:read", "jobs:read", "users:login"})
     */
    private $job;


    public function __construct()
    {
        $this->patients = new ArrayCollection();
        $this->nurses = new ArrayCollection();
        $this->careGiver = new ArrayCollection();
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
            $slugify = new Slugify;
            $this->slug = $slugify->Slugify(strtoupper($this->lastName) . $this->firstName);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAdeli(): ?string
    {
        return $this->adeli;
    }

    public function setAdeli(string $adeli): self
    {
        $this->adeli = $adeli;

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
     * 
     * @return Collection|Patient[]
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(Patient $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients[] = $patient;
            $patient->setDoctor($this);
        }

        return $this;
    }

    public function removePatient(Patient $patient): self
    {
        if ($this->patients->contains($patient)) {
            $this->patients->removeElement($patient);
            // set the owning side to null (unless already changed)
            if ($patient->getDoctor() === $this) {
                $patient->setDoctor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Patient[]
     */
    public function getCareGiver(): Collection
    {
        return $this->careGiver;
    }

    public function addCareGiver(Patient $careGiver): self
    {
        if (!$this->careGiver->contains($careGiver)) {
            $this->careGiver[] = $careGiver;
            $careGiver->addNurse($this);
        }

        return $this;
    }

    public function removeCareGiver(Patient $careGiver): self
    {
        if ($this->careGiver->contains($careGiver)) {
            $this->careGiver->removeElement($careGiver);
            $careGiver->removeNurse($this);
        }

        return $this;
    }

    public function getJob(): ?Job
    {
        return $this->job;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job;

        return $this;
    }


    /**
     * @Groups({"users:login", "patients:read"})
     *
     * @return void
     */
    public function getFullName()
    {
        return $this->getLastName() . " " . $this->getFirstName();
    }

    /**
     * @Groups({"users:login", "users:read"})
     *
     * @return void
     */
    public function getJobTitle()
    {
        return $this->getJob()->getTitle();
    }
}
