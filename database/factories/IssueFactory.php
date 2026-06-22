<?php

namespace Database\Factories;

use App\Models\Issue;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['open', 'in_progress', 'closed']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+3 months'),
        ];
    }
}
