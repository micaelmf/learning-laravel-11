<?php

namespace App\Http\Controllers;

use App\Services\ReminderService;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    protected $taskService;
    protected $reminderService;

    public function __construct(TaskService $taskService, ReminderService $reminderService)
    {
        $this->taskService = $taskService;
        $this->reminderService = $reminderService;
    }

    public function index()
    {
        $userId = Auth::id();
        $cacheKey = "dashboard:tasks:count:{$userId}";

        $countTasksToday = Cache::remember($cacheKey, 60, function () {
            return [
                "doing" => $this->taskService->countTasksWithStatusDoingTodayByUser(),
                "pending" =>  $this->taskService->countTasksWithStatusPendingTodayByUser(),
                "completed" => $this->taskService->countTasksWithStatusCompletedTodayByUser(),
                "allExceptArchived" => $this->taskService->countAllTasksExceptArchivedByUser(),
            ];
        });

        $remindersToday = $this->reminderService->getReminderTodayByUser();

        return view('dashboard', [
            'countTasksToday' => $countTasksToday,
            'remindersToday' => $remindersToday,
        ]);
    }
}
