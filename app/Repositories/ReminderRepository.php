<?php

namespace App\Repositories;

use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

    public function getReminderTodayByUser()
    {
        $today = Carbon::today();
        return $this->reminder->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'sent'])
            ->whereBetween('reminder_time', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->with('task')
            ->orderBy('reminder_time', 'asc')
            ->get();
    }

    public function changeStatus(string $id, string $status)
    {
        $reminder = $this->reminder->findOrFail($id);

        $reminder->status = $status;
        $reminder->save();
    }
}
