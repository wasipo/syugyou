<?php

declare(strict_types=1);

namespace Tests\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\AsUri;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\ValueObjects\TestUserMetadata;
use Tests\Enums\TestUserStatus;

class TestUser extends Model
{
    use HasFactory;

    protected $table = 'test_users';

    protected $fillable = [
        'name',
        'email', 
        'is_active',
        'status',
        'metadata',
        'profile_url'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'status' => TestUserStatus::class,
            'metadata' => AsCollection::of(TestUserMetadata::class),
            'profile_url' => AsUri::class,
        ];
    }

    // Laravel 12.5 - #[Scope] 属性を使用
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    #[Scope]
    protected function byStatus(Builder $query, TestUserStatus $status): Builder
    {
        return $query->where('status', $status);
    }

    // Laravel 12.6 - fillAndInsert
    public static function fillAndInsert(array $records): void
    {
        foreach ($records as $record) {
            static::create($record);
        }
    }

    // Laravel 12.7 - toResource
    public function toResource(string $resourceClass = null): JsonResource
    {
        $resourceClass = $resourceClass ?: \Tests\Resources\TestUserResource::class;
        return new $resourceClass($this);
    }

    public function testPosts(): HasMany
    {
        return $this->hasMany(TestPost::class);
    }
}