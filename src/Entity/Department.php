<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 */
class Department
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $monday;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tuesday;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wednesday;

    /**
     * @ORM\Column(type="boolean")
     */
    private $thursday;

    /**
     * @ORM\Column(type="boolean")
     */
    private $friday;

    /**
     * @ORM\Column(type="boolean")
     */
    private $saturday;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sunday;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $workHours;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonday(): ?bool
    {
        return $this->monday;
    }


    public function setMonday(bool $monday): self
    {
        $this->monday = $monday;

        return $this;
    }


    public function getTuesday(): ?bool
    {
        return $this->tuesday;
    }

    public function setTuesday(bool $tuesday): self
    {
        $this->tuesday = $tuesday;

        return $this;
    }

    public function getWednesday(): ?bool
    {
        return $this->wednesday;
    }

    public function setWednesday(bool $wednesday): self
    {
        $this->wednesday = $wednesday;

        return $this;
    }

    public function getThursday(): ?bool
    {
        return $this->thursday;
    }

    public function setThursday(bool $thursday): self
    {
        $this->thursday = $thursday;

        return $this;
    }

    public function getFriday(): ?bool
    {
        return $this->friday;
    }

    public function setFriday(bool $friday): self
    {
        $this->friday = $friday;

        return $this;
    }

    public function getSaturday(): ?bool
    {
        return $this->saturday;
    }

    public function setSaturday(bool $saturday): self
    {
        $this->saturday = $saturday;

        return $this;
    }

    public function getSunday(): ?bool
    {
        return $this->sunday;
    }

    public function setSunday(bool $sunday): self
    {
        $this->sunday = $sunday;

        return $this;
    }

    public function getWorkHours(): ?string
    {
        return $this->workHours;
    }

    public function setWorkHours(string $workHours): self
    {
        $this->workHours = $workHours;

        return $this;
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function isOpenToday(): bool
    {
        $action = "get" . date('l');
        return $this->$action();
    }

    public function openAtHour(): int
    {
        return (int)explode('-', $this->workHours)[0];
    }

    public function closeAtHour(): int
    {
        return (int)explode('-', $this->workHours)[1];
    }

    public function isStillOpen(): bool
    {
        $nowPlus30MinuteSlot = time() + 60*30;

        return $this->isOpenToday() && (int)date('H', $nowPlus30MinuteSlot) < $this->closeAtHour();
    }
    public function getClosedDaysOfWeek()
    {
        $closedDays = [];

        if ($this->monday === false) {
            $closedDays[] = 1;
        }

        if ($this->tuesday === false) {
            $closedDays[] = 2;
        }

        if ($this->wednesday === false) {
            $closedDays[] = 3;
        }

        if ($this->thursday === false) {
            $closedDays[] = 4;
        }

        if ($this->friday === false) {
            $closedDays[] = 5;
        }

        if ($this->saturday === false) {
            $closedDays[] = 6;
        }

        if ($this->sunday === false) {
            $closedDays[] = 0;
        }

        return $closedDays;
    }
}
