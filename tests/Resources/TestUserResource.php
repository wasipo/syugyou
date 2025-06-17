<?php

declare(strict_types=1);

namespace Tests\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'status' => $this->status,
            'metadata' => $this->metadata,
            'profile_url' => $this->profile_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}