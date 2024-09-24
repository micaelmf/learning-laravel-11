<?php

namespace App\Services;

use App\Repositories\TaskRepository;
use App\Services\ReminderService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskService
{
    protected $taskRepository;
    protected $reminderService;

    public function __construct(TaskRepository $taskRepository, ReminderService $reminderService)
    {
        $this->taskRepository = $taskRepository;
        $this->reminderService = $reminderService;
    }

    public function getTasks(array $params)
    {
        $params = array_filter($params, function ($value) {
            return !empty($value);
        });

        return $this->taskRepository->getTasks($params);
    }

    public function countTasksWithStatusDoingTodayByUser()
    {
        return $this->taskRepository->countTasksWithStatusDoingTodayByUser();
    }

    public function countTasksWithStatusPendingTodayByUser()
    {
        return $this->taskRepository->countTasksWithStatusPendingTodayByUser();
    }

    public function countTasksWithStatusCompletedTodayByUser()
    {
        return $this->taskRepository->countTasksWithStatusCompletedTodayByUser();
    }

    public function countAllTasksExceptArchivedByUser()
    {
        return $this->taskRepository->countAllTasksExceptArchivedByUser();
    }

    public function getTask(string $id)
    {
        return $this->taskRepository->getTask($id);
    }

    public function createTask(array $data)
    {
        DB::beginTransaction();

        try {
            $data['user_id'] = Auth::id();
            $task = $this->taskRepository->createTask($data);
            $this->reminderService->createReminder($task->id, $data);

            DB::commit();
            return $task;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateTask(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $task = $this->taskRepository->updateTask($id, $data);
            $this->reminderService->updateReminder($id, $data);

            DB::commit();
            return $task;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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
