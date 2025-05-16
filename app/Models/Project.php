<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Project extends Model
{
    protected $fillable = ['name'];

    public function members()
    {
        return $this->belongsToMany(Member::class)
            ->withPivot(['assigned_by', 'assigned_at', 'role', 'deleted_at'])
            ->withTimestamps()
            ->using(MemberProject::class);
    }
}
