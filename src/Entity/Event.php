<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $tva = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateStop = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;
    
    #[ORM\ManyToOne(inversedBy: 'event')]
    private ?Eventstrend $eventstrend = null;

    #[ORM\Column(length: 255)]
    private ?string $image1 = null;

    #[ORM\Column(length: 255)]
    private ?string $image2 = null;

    #[ORM\Column(length: 255)]
    private ?string $image3 = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): static
    {
        $this->tva = $tva;

        return $this;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateStop(): ?\DateTimeInterface
    {
        return $this->dateStop;
    }

    public function setDateStop(\DateTimeInterface $dateStop): static
    {
        $this->dateStop = $dateStop;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getEventstrend(): ?Eventstrend
    {
        return $this->eventstrend;
    }

    public function setEventstrend(?Eventstrend $eventstrend): static
    {
        $this->eventstrend = $eventstrend;

        return $this;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(string $image1): static
    {
        $this->image1 = $image1;

        return $this;
    }

    public function getImage2(): ?string
    {
        return $this->image2;
    }

    public function setImage2(string $image2): static
    {
        $this->image2 = $image2;

        return $this;
    }

    public function getImage3(): ?string
    {
        return $this->image3;
    }

    public function setImage3(string $image3): static
    {
        $this->image3 = $image3;

        return $this;
    }
}