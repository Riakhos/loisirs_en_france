<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
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
     * @var Collection<int, Activity>
     */
    #[ORM\OneToMany(targetEntity: Activity::class, mappedBy: 'category')]
    private Collection $activity;

    /**
     * @var Collection<int, Subcategory>
     */
    #[ORM\OneToMany(targetEntity: Subcategory::class, mappedBy: 'category')]
    private Collection $subcategories;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * @var Collection<int, Trend>
     */
    #[ORM\OneToMany(targetEntity: Trend::class, mappedBy: 'category')]
    private Collection $trends;

    /**
     * @var Collection<int, Exclusive>
     */
    #[ORM\OneToMany(targetEntity: Exclusive::class, mappedBy: 'category')]
    private Collection $exclusives;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(targetEntity: Offer::class, mappedBy: 'category')]
    private Collection $offers;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'category')]
    private Collection $events;

    public function __construct()
    {
        $this->activity = new ArrayCollection();
        $this->subcategories = new ArrayCollection();
        $this->trends = new ArrayCollection();
        $this->exclusives = new ArrayCollection();
        $this->offers = new ArrayCollection();
        $this->events = new ArrayCollection();
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

    /**
     * @return Collection<int, Activity>
     */
    public function getActivity(): Collection
    {
        return $this->activity;
    }

    public function addActivity(Activity $activity): static
    {
        if (!$this->activity->contains($activity)) {
            $this->activity->add($activity);
            $activity->setCategory($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        if ($this->activity->removeElement($activity)) {
            // set the owning side to null (unless already changed)
            if ($activity->getCategory() === $this) {
                $activity->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Subcategory>
     */
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(Subcategory $subcategory): static
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories->add($subcategory);
            $subcategory->setCategory($this);
        }

        return $this;
    }

    public function removeSubcategory(Subcategory $subcategory): static
    {
        if ($this->subcategories->removeElement($subcategory)) {
            // set the owning side to null (unless already changed)
            if ($subcategory->getCategory() === $this) {
                $subcategory->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Trend>
     */
    public function getTrends(): Collection
    {
        return $this->trends;
    }

    public function addTrend(Trend $trend): static
    {
        if (!$this->trends->contains($trend)) {
            $this->trends->add($trend);
            $trend->setCategory($this);
        }

        return $this;
    }

    public function removeTrend(Trend $trend): static
    {
        if ($this->trends->removeElement($trend)) {
            // set the owning side to null (unless already changed)
            if ($trend->getCategory() === $this) {
                $trend->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Exclusive>
     */
    public function getExclusives(): Collection
    {
        return $this->exclusives;
    }

    public function addExclusife(Exclusive $exclusife): static
    {
        if (!$this->exclusives->contains($exclusife)) {
            $this->exclusives->add($exclusife);
            $exclusife->setCategory($this);
        }

        return $this;
    }

    public function removeExclusife(Exclusive $exclusife): static
    {
        if ($this->exclusives->removeElement($exclusife)) {
            // set the owning side to null (unless already changed)
            if ($exclusife->getCategory() === $this) {
                $exclusife->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): static
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
            $offer->setCategory($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            // set the owning side to null (unless already changed)
            if ($offer->getCategory() === $this) {
                $offer->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setCategory($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCategory() === $this) {
                $event->setCategory(null);
            }
        }

        return $this;
    }
}