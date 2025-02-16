<?php

namespace App\DTO;

use App\Entity\Activity;
use App\Entity\Event;
use App\Entity\Offer;
use App\Entity\Partner;
use App\Entity\User;

class SearchRating
{
    public ?float $score = null;
    public ?Activity $activity = null;
    public ?Event $event = null;
    public ?Offer $offer = null;
    public ?Partner $partner = null;
    public ?User $user = null;
    public ?string $createdAt = null;
    public ?string $search = null;
}