<?php

namespace App\Repositories;

use App\Models\Reminder;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Facades\Cache;
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
        Cache::forget("reminders:today:" . Auth::id());

        return $this->reminder->create($data);
    }

    /**
     * Update a reminder for a task
     * @param array $reminders
     * @return void
     */
    public function updateReminder(array $data)
    {
        Cache::forget("reminders:today:" . Auth::id());

        $reminder = $this->reminder->where('task_id', $data['task_id'])->first();
        return $reminder->update($data);
    }

    /**
     * Get all reminders for today by user
     * @return mixed
     * @throws InvalidFormatException
     */
    public function getReminderTodayByUser()
    {
        $cacheKey = "reminders:today:" . Auth::id();

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $today = Carbon::today();

        $todayReminders = $this->reminder->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'sent'])
            ->whereBetween('reminder_time', [$today->copy()->startOfDay(), $today->copy()->endOfDay()])
            ->with('task')
            ->orderBy('reminder_time', 'asc')
            ->get();

        Cache::put($cacheKey, $todayReminders, $today->copy()->endOfDay());

        return $todayReminders;
    }

    /**
     * Change the status of a reminder
     * @param string $id
     * @param string $status
     * @return void
     */
    public function changeStatus(string $id, string $status): bool
    {
        Cache::forget("reminders:today:" . Auth::id());

        $reminder = $this->reminder->findOrFail($id);
        return $reminder->update(['status' => $status]);
    }
}
