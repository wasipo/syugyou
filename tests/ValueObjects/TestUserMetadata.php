<?php

declare(strict_types=1);

namespace Tests\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class TestUserMetadata implements Arrayable, JsonSerializable
{
    public function __construct(
        public readonly string $key,
        public readonly mixed $value
    ) {}

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            key: $data['key'] ?? '',
            value: $data['value'] ?? null
        );
    }
}