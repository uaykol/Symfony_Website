<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=250)
     */
    private $company;


    /**
     * @ORM\Column(type="string")
     */
    private $available;

    /**
     * @ORM\Column(type="string")
     */
    private $workhour;


    /**
     * @ORM\Column(type="string")
     */
    private $workdays;


    /**
     * @ORM\Column(type="string")
     */
    private $booking;


    /**
     * @return mixed
     */
    public function getBooking(): ?string
    {
        return $this->booking;
    }


    /**
     * @param mixed $booking
     */
    public function setBooking($booking): self
    {
        $this->booking = $booking;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getWorkdays(): ?string
    {
        return $this->workdays;
    }

    /**
     * @param mixed $workhour
     */
    public function setWorkdays($workdays): self
    {
        $this->workdays = $workdays;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getWorkhour(): ?string
    {
        return $this->workhour;
    }

    /**
     * @param mixed $workhour
     */
    public function setWorkhour($workhour): self
    {
        $this->workhour = $workhour;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getAvailable(): ?string
    {
        return $this->available;
    }

    /**
     * @param mixed $available
     */
    public function setAvailable($available): self
    {
        $this->available = $available;

        return $this;
    }


    public function getCompany(): ?string //test: Getter
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany(string $company): self //test: setter
    {
        $this->company = $company;

        return $this;
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
}
