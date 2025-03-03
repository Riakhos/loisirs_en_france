<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OfferRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
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

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $tva = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'offer')]
    private ?Eventstrend $eventstrend = null;

    /**
     * @var Collection<int, Activity>
     */
    #[Assert\Count(
        min: 0,
        max: 3,
        maxMessage: "Vous ne pouvez associer que trois activités maximum."
    )]
    #[ORM\ManyToMany(targetEntity: Activity::class, inversedBy: 'offers')]
    private Collection $activity;

    #[ORM\ManyToOne(targetEntity: Partner::class, inversedBy: 'offers', fetch: 'EAGER')]
    private ?Partner $partners = null;

    #[ORM\Column]
    private ?int $peopleCount = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'offers')]
    private Collection $tags;

    /**
     * @var Collection<int, Rating>
     */
    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'offer')]
    private Collection $ratings;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->activity = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->ratings = new ArrayCollection();
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

    /**
     * @return Collection<int, Activity>
     */
    public function getActivity(): Collection
    {
        return $this->activity;
    }

    public function addActivity(Activity $activity): static
    {
        if ($this->activity->count() >= 3) {
            throw new \Exception("Une offre ne peut avoir que 3 activités.");
        }

        if (!$this->activity->contains($activity)) {
            $this->activity->add($activity);
            $activity->addOffer($this);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): static
    {
        $this->activity->removeElement($activity);

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

    public function getPeopleCount(): ?int
    {
        return $this->peopleCount;
    }

    public function setPeopleCount(int $peopleCount): static
    {
        $this->peopleCount = $peopleCount;

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
            $tag->addOffer($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeOffer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setOffer($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getOffer() === $this) {
                $rating->setOffer(null);
            }
        }

        return $this;
    }
    
    public function getAverageRating(): ?float
    {
        $ratings = $this->ratings->toArray();
        if (count($ratings) === 0) {
            return null;
        }

        $sum = array_reduce($ratings, fn($carry, $rating) => $carry + $rating->getScore(), 0);
        return round($sum / count($ratings), 1);
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}