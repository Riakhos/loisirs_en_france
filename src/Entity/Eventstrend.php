<?php

namespace App\Entity;

use App\Repository\EventstrendRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventstrendRepository::class)]
class Eventstrend
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * @var Collection<int, Trend>
     */
    #[ORM\OneToMany(targetEntity: Trend::class, mappedBy: 'eventstrend')]
    private Collection $trend;

    /**
     * @var Collection<int, Exclusive>
     */
    #[ORM\OneToMany(targetEntity: Exclusive::class, mappedBy: 'eventstrend')]
    private Collection $exclusive;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'eventstrend')]
    private Collection $offer;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'eventstrend')]
    private Collection $event;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {
        $this->trend = new ArrayCollection();
        $this->exclusive = new ArrayCollection();
        $this->offer = new ArrayCollection();
        $this->event = new ArrayCollection();;
    }

    public function __toString()
    {
        return $this->name;
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

    /**
     * @return Collection<int, Trend>
     */
    public function getTrend(): Collection
    {
        return $this->trend;
    }

    public function addTrend(Trend $trend): static
    {
        if (!$this->trend->contains($trend)) {
            $this->trend->add($trend);
            $trend->setEventstrend($this);
        }

        return $this;
    }

    public function removeTrend(Trend $trend): static
    {
        if ($this->trend->removeElement($trend)) {
            // set the owning side to null (unless already changed)
            if ($trend->getEventstrend() === $this) {
                $trend->setEventstrend(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Exclusive>
     */
    public function getExclusive(): Collection
    {
        return $this->exclusive;
    }

    public function addExclusive(Exclusive $exclusive): static
    {
        if (!$this->exclusive->contains($exclusive)) {
            $this->exclusive->add($exclusive);
            $exclusive->setEventstrend($this);
        }

        return $this;
    }

    public function removeExclusive(Exclusive $exclusive): static
    {
        if ($this->exclusive->removeElement($exclusive)) {
            // set the owning side to null (unless already changed)
            if ($exclusive->getEventstrend() === $this) {
                $exclusive->setEventstrend(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffer(): Collection
    {
        return $this->offer;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offer->contains($offer)) {
            $this->offer->add($offer);
            $offer->setEventstrend($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offer->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getEventstrend() === $this) {
                $offer->setEventstrend(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->event->contains($event)) {
            $this->event->add($event);
            $event->setEventstrend($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->event->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getEventstrend() === $this) {
                $event->setEventstrend(null);
            }
        }

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}