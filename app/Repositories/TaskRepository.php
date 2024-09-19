<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function getTasks(array $params)
    {
        $query = $this->task->query();

        $query = $this->applyFilters($query, $params);

        return $query->latest()->paginate(10);
    }

    public function getTask(string $id)
    {
        return $this->task->findOrFail($id)->load('reminders');
    }

    protected function applyFilters($query, array $params)
    {
        if (isset($params['term'])) {
            $query = $this->applyTermFilter($query, $params['term']);
        }

        if (isset($params['status'])) {
            $query = $this->applyStatusFilter($query, $params['status']);
        }

        return $query;
    }

    protected function applyTermFilter($query, $term)
    {
        return $query->where(function ($queryBuilder) use ($term) {
            $queryBuilder->where('name', 'LIKE', "%{$term}%")
                ->orWhere('description', 'LIKE', "%{$term}%");
        });
    }

    protected function applyStatusFilter($query, $status)
    {
        if ($status !== 'deleted') {
            return $query->where('status', $status);
        }

        return $query->onlyTrashed();
    }

    public function createTask(array $data)
    {
        return $this->task->create($data);
    }

    public function updateTask(string $id, array $data)
    {
        $task = $this->task->findOrFail($id);

        $task->update($data);

        return $task;
    }

    public function deleteTask(string $id)
    {
        $task = $this->task->findOrFail($id);

        return $task->delete();
    }

    public function restoreTask(string $id)
    {
        $task = $this->task->withTrashed()->findOrFail($id);

        return $task->restore();
    }

    public function changeStatus(string $id, string $status)
    {
        $task = $this->task->findOrFail($id);

        $task->status = $status;

        return $task->save();
    }
}
