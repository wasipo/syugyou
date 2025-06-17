<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Casts\AsUri;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestUser extends Model
{
    use HasFactory;

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
    protected function active(Builder $query): void
    {
        $query->where('is_active', true);
    }

    #[Scope]
    protected function byStatus(Builder $query, TestUserStatus $status): void
    {
        $query->where('status', $status);
    }
}