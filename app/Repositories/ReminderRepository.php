<?php

namespace App\Repositories;

use App\Models\Reminder;

class ReminderRepository
{
    protected $reminder;

    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Create a reminder for a task
     * @param array $data
     * @return mixed
     */
    public function createReminder(array $data): Reminder
    {
        return $this->reminder->create($data);
    }

    /**
     * Update a reminder for a task
     * @param array $reminders
     * @return void
     */
    public function updateReminder(array $data)
    {
        $reminder = $this->reminder->where('task_id', $data['task_id'])->first();
        return $reminder->update($data);
    }
}
