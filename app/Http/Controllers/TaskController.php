<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get all params from the request
        $params = $request->all();
        $tasks = $this->taskService->getTasks($params);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,doing,completed'
        ]);

        $this->taskService->createTask($request->all());

        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = $this->taskService->getTask($id);

        return view('tasks.form', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,doing,completed'
        ]);

        $this->taskService->updateTask($id, $request->all());

        return redirect()->route('tasks.index');
    }

    public function changeStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,doing,completed,archived'
        ]);

        $this->taskService->changeStatus($id, $request->status);

        return response()->json([
            'message' => 'Status changed successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->taskService->deleteTask($id);

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}
