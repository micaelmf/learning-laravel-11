<?php

namespace App\Services;

use App\Repositories\TaskRepository;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getTasks(array $params)
    {
        $params = array_filter($params, function ($value) {
            return !empty($value);
        });

        return $this->taskRepository->getTasks($params);
    }

    public function getTask(string $id)
    {
        return $this->taskRepository->getTask($id);
    }

    public function createTask(array $data)
    {
        $task = $this->taskRepository->createTask($data);

        if (isset($data['reminder'])) {
            // converter o formato 30_minutes_before em uma data baseado na data de vencimento
            $data['reminder'] = array_map(function ($reminder) use ($task) {
                $explode = explode('_', $reminder);
                $timeQuantity = $explode['0'];
                $timeUnit = $explode['1'];
                $reminderDate = date('Y-m-d H:i:s', strtotime($task->due_date . ' - ' . $timeQuantity . ' ' . $timeUnit));

                return [
                    'reminder_date' => $reminderDate,
                    'user_id' => auth()->id(),
                ];
            }, $data['reminder']);

            dd($data['reminder']);

            $task->reminders()->createMany($data['reminder']);
        }

        return $task;
    }

    public function updateTask(string $id, array $data)
    {
        return $this->taskRepository->updateTask($id, $data);
    }

    public function deleteTask(string $id)
    {
        return $this->taskRepository->deleteTask($id);
    }

    public function restoreTask(string $id)
    {
        return $this->taskRepository->restoreTask($id);
    }

    public function changeStatus(string $id, string $status)
    {
        return $this->taskRepository->changeStatus($id, $status);
    }
}
