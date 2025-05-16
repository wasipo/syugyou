<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;


class MemberProject extends Pivot
{
    use SoftDeletes;
    use HasFactory;

    protected $table = 'member_project';
    protected $dates = ['deleted_at'];
}

