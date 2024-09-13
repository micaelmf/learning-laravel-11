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
        return $this->taskRepository->createTask($data);
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

    public function complete(string $id)
    {
        return $this->taskRepository->complete($id);
    }
}
