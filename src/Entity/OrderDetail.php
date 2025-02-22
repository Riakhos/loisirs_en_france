<?php

namespace App\Entity;

use App\Repository\OrderDetailRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDetailRepository::class)]
class OrderDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'orderDetails')]
    private ?Order $myOrder = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activityName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eventName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $offerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $activityImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $eventImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $offerImage = null;

    #[ORM\Column(nullable: true)]
    private ?int $activityQuantity = null;

    #[ORM\Column(nullable: true)]
    private ?int $eventQuantity = null;

    #[ORM\Column(nullable: true)]
    private ?int $offerQuantity = null;

    #[ORM\Column(nullable: true)]
    private ?float $activityPrice = null;

    #[ORM\Column(nullable: true)]
    private ?float $eventPrice = null;

    #[ORM\Column(nullable: true)]
    private ?float $offerPrice = null;

    #[ORM\Column(nullable: true)]
    private ?float $activityTva = null;

    #[ORM\Column(nullable: true)]
    private ?float $eventTva = null;

    #[ORM\Column(nullable: true)]
    private ?float $offerTva = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partnerAddress = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partnerCity = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partnerName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partnerPostal = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $time = null;

    #[ORM\Column]
    private ?int $itemId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $partnerWebsite = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $partnerPhone = null;

    #[ORM\Column(length: 180, nullable: true)]
    private ?string $partnerEmail = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMyOrder(): ?Order
    {
        return $this->myOrder;
    }

    public function setMyOrder(?Order $myOrder): static
    {
        $this->myOrder = $myOrder;

        return $this;
    }

    public function getActivityName(): ?string
    {
        return $this->activityName;
    }

    public function setActivityName(string $activityName): static
    {
        $this->activityName = $activityName;

        return $this;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): static
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getOfferName(): ?string
    {
        return $this->offerName;
    }

    public function setOfferName(string $offerName): static
    {
        $this->offerName = $offerName;

        return $this;
    }

    public function getActivityImage(): ?string
    {
        return $this->activityImage;
    }

    public function setActivityImage(string $activityImage): static
    {
        $this->activityImage = $activityImage;

        return $this;
    }

    public function getEventImage(): ?string
    {
        return $this->eventImage;
    }

    public function setEventImage(string $eventImage): static
    {
        $this->eventImage = $eventImage;

        return $this;
    }

    public function getOfferImage(): ?string
    {
        return $this->offerImage;
    }

    public function setOfferImage(string $offerImage): static
    {
        $this->offerImage = $offerImage;

        return $this;
    }

    public function getActivityQuantity(): ?int
    {
        return $this->activityQuantity;
    }

    public function setActivityQuantity(int $activityQuantity): static
    {
        $this->activityQuantity = $activityQuantity;

        return $this;
    }

    public function getEventQuantity(): ?int
    {
        return $this->eventQuantity;
    }

    public function setEventQuantity(int $eventQuantity): static
    {
        $this->eventQuantity = $eventQuantity;

        return $this;
    }

    public function getOfferQuantity(): ?int
    {
        return $this->offerQuantity;
    }

    public function setOfferQuantity(int $offerQuantity): static
    {
        $this->offerQuantity = $offerQuantity;

        return $this;
    }

    public function getActivityPrice(): ?float
    {
        return $this->activityPrice;
    }

    public function setActivityPrice(float $activityPrice): static
    {
        $this->activityPrice = $activityPrice;

        return $this;
    }

    public function getEventPrice(): ?float
    {
        return $this->eventPrice;
    }

    public function setEventPrice(float $eventPrice): static
    {
        $this->eventPrice = $eventPrice;

        return $this;
    }

    public function getOfferPrice(): ?float
    {
        return $this->offerPrice;
    }

    public function setOfferPrice(float $offerPrice): static
    {
        $this->offerPrice = $offerPrice;

        return $this;
    }

    public function getActivityTva(): ?float
    {
        return $this->activityTva;
    }

    public function setActivityTva(float $activityTva): static
    {
        $this->activityTva = $activityTva;

        return $this;
    }

    public function getEventTva(): ?float
    {
        return $this->eventTva;
    }

    public function setEventTva(float $eventTva): static
    {
        $this->eventTva = $eventTva;

        return $this;
    }

    public function getOfferTva(): ?float
    {
        return $this->offerTva;
    }

    public function setOfferTva(float $offerTva): static
    {
        $this->offerTva = $offerTva;

        return $this;
    }

    public function getPartnerAddress(): ?string
    {
        return $this->partnerAddress;
    }

    public function setPartnerAddress(?string $partnerAddress): static
    {
        $this->partnerAddress = $partnerAddress;

        return $this;
    }

    public function getPartnerCity(): ?string
    {
        return $this->partnerCity;
    }

    public function setPartnerCity(?string $partnerCity): static
    {
        $this->partnerCity = $partnerCity;

        return $this;
    }

    public function getPartnerName(): ?string
    {
        return $this->partnerName;
    }

    public function setPartnerName(?string $partnerName): static
    {
        $this->partnerName = $partnerName;

        return $this;
    }

    public function getPartnerPostal(): ?string
    {
        return $this->partnerPostal;
    }

    public function setPartnerPostal(?string $partnerPostal): static
    {
        $this->partnerPostal = $partnerPostal;

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

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function setItemId(int $itemId): static
    {
        $this->itemId = $itemId;

        return $this;
    }

    public function getPartnerWebsite(): ?string
    {
        return $this->partnerWebsite;
    }

    public function setPartnerWebsite(?string $partnerWebsite): static
    {
        $this->partnerWebsite = $partnerWebsite;

        return $this;
    }

    public function getPartnerPhone(): ?string
    {
        return $this->partnerPhone;
    }

    public function setPartnerPhone(?string $partnerPhone): static
    {
        $this->partnerPhone = $partnerPhone;

        return $this;
    }

    public function getPartnerEmail(): ?string
    {
        return $this->partnerEmail;
    }

    public function setPartnerEmail(?string $partnerEmail): static
    {
        $this->partnerEmail = $partnerEmail;

        return $this;
    }

    public function getProductName(): ?string
    {
        if ($this->getActivityName()) {
            return $this->getActivityName();
        } elseif ($this->getEventName()) {
            return $this->getEventName();
        } elseif ($this->getOfferName()) {
            return $this->getOfferName();
        }

        return null;
    }
    
    public function getProductImage(): ?string
    {
        if ($this->getActivityImage()) {
            return $this->getActivityImage();
        } elseif ($this->getEventImage()) {
            return $this->getEventImage();
        } elseif ($this->getOfferImage()) {
            return $this->getOfferImage();
        }

        return null;
    }

    public function getProductPrice(): ?float
    {
        return $this->getActivityPrice() + $this->getEventPrice() + $this->getOfferPrice();
    }

    public function getProductTva(): ?float
    {
        return $this->getActivityTva() + $this->getEventTva() + $this->getOfferTva();
    }
    
    public function getProductQuantity(): ?float
    {
        return $this->getActivityQuantity() + $this->getEventQuantity() + $this->getOfferQuantity();
    }

    public function getTotalHt()
    {
        $totalHT = 0;
        
        // Récupération des quantités
        $totalQuantity = $this->getActivityQuantity() + $this->getEventQuantity() + $this->getOfferQuantity();

        if ($this->getProductTva() > 0) {
            $coeff = 1 + ($this->getProductTva() / 100);
            $priceHT = $this->getProductPrice() / $coeff; // Conversion du prix TTC en HT
        } else {
            $priceHT = $this->getProductPrice(); // Pas de TVA, donc prix HT = prix TTC
        }

        $totalHT += $priceHT * $totalQuantity;
        
        return $totalHT;
    }

    public function getTotalTva()
    {
        $totalTva = 0;

        // Récupération des quantités
        $totalQuantity = $this->getActivityQuantity() + $this->getEventQuantity() + $this->getOfferQuantity();

        if ($this->getProductTva() > 0) {
            $coeff = 1 + ($this->getProductTva() / 100);
            $priceHT = $this->getProductPrice() / $coeff; // Prix HT
            $tva = $this->getProductPrice() - $priceHT; // TVA = Prix TTC - Prix HT
        } else {
            $tva = 0; // Pas de TVA
        }

        $totalTva += $tva * $totalQuantity;
        
        return $totalTva;
    }

    public function getTotalTtc()
    {
        return $this->getTotalHt() + $this->getTotalTva();
    }

}