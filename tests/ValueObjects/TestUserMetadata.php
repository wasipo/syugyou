<?php

declare(strict_types=1);

namespace Tests\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class TestUserMetadata implements Arrayable, JsonSerializable
{
    public function __construct(array|string $keyOrData, mixed $value = null)
    {
        if (is_array($keyOrData)) {
            // AsCollection::ofから配列として渡された場合
            $this->key = $keyOrData['key'] ?? '';
            $this->value = $keyOrData['value'] ?? null;
        } else {
            // 直接のパラメータとして渡された場合
            $this->key = $keyOrData;
            $this->value = $value;
        }
    }

    public readonly string $key;
    public readonly mixed $value;

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
        return new self($data);
    }

    // AsCollection::ofで必要なファクトリメソッド
    public static function make(array $data): self
    {
        return self::fromArray($data);
    }

    // 文字列の場合はJSONデコードして処理
    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        return self::fromArray($data);
    }
}