<?php
// database/factories/ReminderFactory.php

namespace Database\Factories;

use App\Models\Reminder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reminder>
 */
class ReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'preset_time' => $this->faker->randomElement([
                '5_minutes_before',
                '10_minutes_before',
                '30_minutes_before',
                '1_hour_before',
                '1_day_before',
            ]),
            'reminder_time' => null,
            'status' => $this->faker->randomElement(['pending', 'canceled', 'sent', 'visualized']),
            'send_by' => $this->faker->randomElement(['popup', 'email', 'sms']),
            'job_id' => null,
            'task_id' => null,
            'user_id' => null,
        ];
    }

    /**
     * Define the model's state with existing user and task IDs.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withUserAndTask($userId, $taskId, $dueDate): Factory
    {
        return $this->state(function (array $attributes) use ($userId, $taskId, $dueDate) {
            return [
                'user_id' => $userId,
                'task_id' => $taskId,
                'due_date' => $dueDate,
            ];
        });
    }
}
