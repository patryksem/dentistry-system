<?php

namespace App\Entity;

use App\Repository\VisitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=VisitRepository::class)
 *
 * @UniqueEntity(
 *     fields={"doctorId", "visitDate"},
 *     errorPath="visitDate",
 *     message="Termin niedostepny"
 * )
 *
 */
class Visit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="visits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patientId;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="patients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $doctorId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $visitDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="smallint")
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatientId(): ?User
    {
        return $this->patientId;
    }

    public function setPatientId(?User $patientId): self
    {
        $this->patientId = $patientId;

        return $this;
    }

    public function getDoctorId(): ?User
    {
        return $this->doctorId;
    }

    public function setDoctorId(?User $doctorId): self
    {
        $this->doctorId = $doctorId;

        return $this;
    }

    public function getVisitDate(): ?\DateTimeInterface
    {
        return $this->visitDate;
    }

    public function setVisitDate(\DateTimeInterface $visitDate): self
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

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
}
