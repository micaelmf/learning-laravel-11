<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        Task::factory()
            ->count(100)
            ->make()
            ->each(function ($task) use ($users) {
                $task->user_id = $users->random()->id;
                $task->save();
            });
    }
}
