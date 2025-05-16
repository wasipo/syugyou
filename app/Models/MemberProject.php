<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;


class MemberProject extends Pivot
{
    use SoftDeletes;
    protected $table = 'member_project';
    protected $dates = ['deleted_at'];
}

