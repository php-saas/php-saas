<?php

namespace App\DTOs;

use Laravel\Paddle\Checkout;

class BillingPlanDTO
{
    /**
     * @param  array<int, string>  $features
     * @param  array<string, mixed>  $options
     */
    public function __construct(
        private string $name,
        private string $description,
        private string $billing = 'monthly',
        private float $price = 0.0,
        private string $priceId = '',
        private string $motivationText = '',
        private array $features = [],
        private array $options = [],
        private ?Checkout $checkout = null,
        private bool $active = true,
    ) {}

    public static function make(): self
    {
        return new self(
            name: '',
            description: '',
            billing: '',
            price: 0.0,
            priceId: '',
            motivationText: '',
            features: [],
            options: [],
            checkout: null,
            active: true
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        /** @var array<int, string> $features */
        $features = $data['features'] ?? [];

        /** @var array<string, mixed> $options */
        $options = $data['options'] ?? [];

        return new self(
            name: $data['name'] && is_string($data['name']) ? $data['name'] : '',
            description: $data['description'] && is_string($data['description']) ? $data['description'] : '',
            billing: $data['billing'] && is_string($data['billing']) ? $data['billing'] : 'monthly',
            price: $data['price'] && is_numeric($data['price']) ? (float) $data['price'] : 0.0,
            priceId: $data['price_id'] && is_string($data['price_id']) ? $data['price_id'] : '',
            motivationText: $data['motivation_text'] && is_string($data['motivation_text']) ? $data['motivation_text'] : '',
            features: $features,
            options: $options,
            checkout: null,
            active: isset($data['active']) && is_bool($data['active']) ? $data['active'] : true
        );
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function billing(string $billing): self
    {
        $this->billing = $billing;

        return $this;
    }

    public function price(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function priceId(string $priceId): self
    {
        $this->priceId = $priceId;

        return $this;
    }

    public function motivationText(string $motivationText): self
    {
        $this->motivationText = $motivationText;

        return $this;
    }

    /**
     * @param  array<int, string>  $features
     */
    public function features(array $features): self
    {
        $this->features = $features;

        return $this;
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function checkout(?Checkout $checkout): self
    {
        $this->checkout = $checkout;

        return $this;
    }

    public function active(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getBilling(): string
    {
        return $this->billing;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getPriceId(): string
    {
        return $this->priceId;
    }

    public function getMotivationText(): string
    {
        return $this->motivationText;
    }

    /**
     * @return array<int, string>
     */
    public function getFeatures(): array
    {
        return $this->features;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    public function getCheckout(): ?Checkout
    {
        return $this->checkout;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return array{
     *     name: string,
     *     description: string,
     *     billing: string,
     *     price_id: string,
     *     motivation_text: string,
     *     features: array<int, string>,
     *     options: array<string, mixed>,
     *     active: bool
     * }
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'billing' => $this->billing,
            'price' => $this->price,
            'price_id' => $this->priceId,
            'motivation_text' => $this->motivationText,
            'features' => $this->features,
            'options' => $this->options,
            'checkout' => $this->checkout ? [
                'transaction' => $this->checkout->getTransaction(),
                'customer' => $this->checkout->getCustomer(),
                'items' => $this->checkout->getItems(),
                'custom_data' => $this->checkout->getCustomData(),
                'return_url' => $this->checkout->getReturnUrl(),
            ] : null,
            'active' => $this->active,
        ];
    }
}
