<?php

namespace App\DTOs;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class BillingPlanDTO extends Data
{
    public function __construct(
        public string $name,
        public string $description,
        public string $billing = 'monthly',
        public ?string $priceId = null,
        public ?float $price = null,
        public ?string $motivationText = null,
        public array $features = [],
        public array $options = [],
        public bool $archived = false
    ) {}
}
