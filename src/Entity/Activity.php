<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
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

    #[ORM\Column(length: 255)]
    private ?string $image = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image3 = null;
    
    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $tva = null;
    
    #[ORM\Column(nullable: true)]
    private ?int $peopleCount = null;
    
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: "activities")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(targetEntity: Subcategory::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Subcategory $subcategory = null;

    #[ORM\ManyToOne(targetEntity: Exclusive::class, inversedBy: 'activities')]
    #[ORM\JoinColumn(onDelete: "SET NULL")]
    private ?Exclusive $exclusive = null;
    
    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?Trend $trend = null;

    #[ORM\ManyToOne(inversedBy: 'activities', fetch: 'EAGER')]
    private ?Partner $partners = null;
    
    /**
     * @var Collection<int, Offer>
     */
    #[ORM\ManyToMany(targetEntity: Offer::class, mappedBy: 'activity')]
    private Collection $offers;
    
    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'activities')]
    private Collection $tags;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
        $this->tags = new ArrayCollection();
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

    public function setImage2(?string $image2): static
    {
        $this->image2 = $image2;

        return $this;
    }

    public function getImage3(): ?string
    {
        return $this->image3;
    }

    public function setImage3(?string $image3): static
    {
        $this->image3 = $image3;

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

    public function getPriceWt()
    {
        $coeff = 1 + ($this->tva/100);
        
        return $this->price / $coeff;
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getSubcategory(): ?Subcategory
    {
        return $this->subcategory;
    }

    public function setSubcategory(?Subcategory $subcategory): static
    {
        $this->subcategory = $subcategory;

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
        }

        return $this;
    }

    public function removeOffer(Offer $offer): static
    {
        if ($this->offers->removeElement($offer)) {
            $offer->removeActivity($this);
        }

        return $this;
    }

    public function getDiscountedPrice(): float
    {
        $now = new \DateTime();

        /** @var Exclusive $exclusive */
        foreach ($this->exclusive as $exclusive) {
            if ($exclusive->getDateStart() <= $now && $exclusive->getDateStop() >= $now) {
                $discount = $exclusive->getDiscountPercentage() / 100;
                return $this->price * (1 - $discount);
            }
        }

        return $this->price;
    }

    public function getDiscountedPriceWt(): float
    {
        $now = new \DateTime();

        /** @var Exclusive $exclusive */
        foreach ($this->exclusive as $exclusive) {
            if ($exclusive->getDateStart() <= $now && $exclusive->getDateStop() >= $now) {
                $discount = $exclusive->getDiscountPercentage() / 100;
                $discountedPrice = $this->price * (1 - $discount);
                $coeff = 1 + ($this->tva / 100);
                return $discountedPrice * $coeff;
            }
        }

        return $this->getPriceWt();
    }

    public function getExclusive(): ?Exclusive
    {
        return $this->exclusive;
    }

    public function setExclusive(?Exclusive $exclusive): static
    {
        $this->exclusive = $exclusive;

        return $this;
    }

    public function getPeopleCount(): ?int
    {
        return $this->peopleCount;
    }

    public function setPeopleCount(?int $peopleCount): static
    {
        $this->peopleCount = $peopleCount;

        return $this;
    }

    public function getTrend(): ?Trend
    {
        return $this->trend;
    }

    public function setTrend(?Trend $trend): static
    {
        $this->trend = $trend;

        return $this;
    }

    public function getPartners(): ?Partner
    {
        return $this->partners;
    }

    public function setPartners(?Partner $partners): static
    {
        $this->partners = $partners;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addActivity($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeActivity($this);
        }

        return $this;
    }
}