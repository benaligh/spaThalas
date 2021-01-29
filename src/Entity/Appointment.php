<?php

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=AppointmentRepository::class)
 */
class Appointment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="champs vide")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="champs vide")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank(message="champs vide")
     * @Assert\Email(message="tapez un email valide")
     */
    private $email;

    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     */
    private $status;

    /**
     * @ORM\ManyToMany(targetEntity=Service::class, inversedBy="appointments")
     * @Assert\NotBlank(message="champs vide")
     */
    private $services;

    /**
     * @ORM\ManyToMany(targetEntity=Option::class, inversedBy="appointments")
     * @Assert\NotBlank(message="champs vide")
     */
    private $Options;

    public function __construct()
    {
        $this->services = new ArrayCollection();
        $this->Options = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Service[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Service $service): self
    {
        if (!$this->services->contains($service)) {
            $this->services[] = $service;
        }

        return $this;
    }

    public function removeService(Service $service): self
    {
        $this->services->removeElement($service);

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->Options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->Options->contains($option)) {
            $this->Options[] = $option;
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        $this->Options->removeElement($option);

        return $this;
    }
}
