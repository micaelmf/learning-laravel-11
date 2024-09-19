<?php

namespace Database\Seeders;

use App\Models\Reminder;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Services\ReminderService;

class ReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $tasks = Task::all();
        $reminderService = app(ReminderService::class);

        $tasks->each(function ($task) use ($users, $reminderService) {
            if (!$task->reminder) { // Verifica se a tarefa já possui um lembrete
                $reminder = Reminder::factory()->make();
                $reminder->user_id = $users->random()->id;
                $reminder->task_id = $task->id;
                $reminder->reminder_time = $reminderService->handlerReminderTime($reminder->preset_time, $task->due_date);
                $reminder->save();
            }
        });
    }
}
