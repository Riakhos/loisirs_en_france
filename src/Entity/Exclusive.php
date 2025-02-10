<?php

namespace App\Entity;

use App\Repository\ExclusiveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExclusiveRepository::class)]
class Exclusive
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateStop = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'exclusive')]
    private ?Eventstrend $eventstrend = null;

    #[ORM\Column]
    private ?float $discountPercentage = null;

    /**
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'exclusive')]
    private Collection $activities;

    #[ORM\Column]
    private ?int $peopleCount = null;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
    }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
    
    public function getDiscountPercentage(): ?float
    {
        return $this->discountPercentage;
    }

    public function setDiscountPercentage(float $discountPercentage): static
    {
        $this->discountPercentage = $discountPercentage;

        return $this;
    }
    
    /**
     * Retourne le prix final TTC après réduction
     */
    public function getPrice(): float
    {
        if ($this->activities->isEmpty() || !$this->activities->first()->getPrice()) {
            return 0;
        }

        return $this->activities->first()->getPrice() * (1 - $this->discountPercentage / 100);
    }

    /**
     * getPriceWt
     *
     * @return float
     */
    public function getPriceWt(): float
    {
        if ($this->activities->isEmpty()) {
            return 0;
        }

        // Récupérer la TVA de la première activité associée
        $activity = $this->activities->first();
        $tva = $activity->getTva(); // Supposant que la méthode getTva() existe dans Activity

        // Calcul du prix TTC
        $coeff = 1 + ($tva / 100);
        return $this->getPrice() / $coeff;
    }

    /**
     * getTva()
     *
     * @return float|null
     */
    public function getTva(): ?float
    {
        if ($this->activities->isEmpty()) {
            return null; // Aucune activité associée
        }

        // Récupérer la TVA de la première activité associée
        $activity = $this->activities->first();

        return $activity->getTva(); // Supposant que Activity a bien une méthode getTva()
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
            $activity->setExclusive($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activities->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getExclusive() === $this) {
                $activity->setExclusive(null);
            }
        }

        return $this;
    }

    public function getPeopleCount(): ?int
    {
        return $this->peopleCount;
    }

    public function setPeopleCount(int $peopleCount): static
    {
        $this->peopleCount = $peopleCount;

        return $this;
    }
}