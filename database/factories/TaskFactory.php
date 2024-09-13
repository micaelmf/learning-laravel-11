<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Nette\Utils\Random;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
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
            'name' => $this->faker->sentence,
            'description' => Random::generate(1) == 1 ? $this->faker->paragraph : null,
            'due_date' => $this->faker->date,
            'status' => $this->faker->randomElement(['pending', 'doing', 'completed', 'archived']),
            'deleted_at' => Random::generate(1) == 1 ? now() : null,
        ];
    }
}
