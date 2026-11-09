<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(2),
            'due_date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
            'class_name' => 'Kelas '.fake()->randomElement(['10A', '10B', '11A', '11B', '12A', '12B']),
            'created_by' => User::factory()->guru(),
        ];
    }
}
