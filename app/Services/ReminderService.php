<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Repositories\ReminderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReminderService
{
    protected $reminderRepository;

    public function __construct(ReminderRepository $reminderRepository)
    {
        $this->reminderRepository = $reminderRepository;
    }

    /**
     * Create a reminder for a task
     * @param int $taskId
     * @param array $data
     * @return mixed
     */
    public function createReminder(int $taskId, array $data)
    {
        $reminder = $this->handleReminders($taskId, $data);

        return $this->reminderRepository->createReminder($reminder, $taskId);
    }

    /**
     * Update reminders for a task
     * @param int $taskId
     * @param array $data
     * @return mixed
     */
    public function updateReminder(int $taskId, array $data)
    {
        $reminder = $this->handleReminders($taskId, $data);

        return $this->reminderRepository->updateReminder($reminder, $taskId);
    }

    public function getReminderTodayByUser()
    {
        return $this->reminderRepository->getReminderTodayByUser();
    }

    public function handleReminders(int $taskId, array $data): array
    {
        $status = 'pending';

        if (
            $data['status'] === 'completed'
            || $data['status'] === 'archived'
            || $data['due_date'] < date('Y-m-d H:i:s')
        ) {
            $status = 'canceled';
        }

        return [
            'status' => $status,
            'preset_time' => $data['preset_time'],
            'reminder_time' => $this->handlerReminderTime($data['preset_time'], $data['due_date']),
            'user_id' => Auth::id(),
            'task_id' => $taskId
        ];
    }

    public function handlerReminderTime($presetTime, $dueDate)
    {
        $explode = explode('_', $presetTime);
        $timeQuantity = $explode['0'];
        $timeUnit = $explode['1'];

        return date('Y-m-d H:i:s', strtotime($dueDate . ' - ' . $timeQuantity . ' ' . $timeUnit));
    }

    public function changeStatus(string $id, string $status)
    {
        return $this->reminderRepository->changeStatus($id, $status);
    }
}
