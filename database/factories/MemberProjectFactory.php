<?php

namespace Database\Factories;

use App\Models\MemberProject;
use App\Models\Member;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class MemberProjectFactory extends Factory
{
    protected $model = MemberProject::class;

    public function definition()
    {
        return [
            'member_id' => Member::factory(),
            'project_id' => Project::factory(),
            'assigned_by' => $this->faker->name,
            'assigned_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'role' => $this->faker->randomElement(['developer', 'designer', 'manager']),
            'deleted_at' => null,
        ];
    }
}