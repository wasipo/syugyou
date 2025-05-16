<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['name'];

    public function projects()
    {
        return $this->belongsToMany(Project::class)
            ->withPivot(['assigned_by', 'assigned_at', 'role', 'deleted_at'])
            ->withTimestamps()
            ->using(MemberProject::class);
    }
    
}
