<?php

declare(strict_types=1);

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestPost extends Model
{
    use HasFactory;

    protected $table = 'test_posts';

    protected $fillable = [
        'title',
        'content',
        'test_user_id',
    ];

    public function testUser(): BelongsTo
    {
        return $this->belongsTo(TestUser::class);
    }
}