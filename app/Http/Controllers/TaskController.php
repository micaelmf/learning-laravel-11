<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = request()->input('query');
        $status = request()->input('status');
        $tasks = Task::query();

        if ($query) {
            $tasks->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%");
            });
        }

        if ($status) {
            $tasks->where('status', $status);
        }

        $tasks = $tasks->latest()
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,doing,completed'
        ]);

        $task = new Task();
        $task->name = $request->name;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->status = $request->status;

        $task->save();

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
        $task = Task::findOrFail($id);

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,doing,completed'
        ]);

        $task = Task::findOrFail($id);
        $task->name = $request->name;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->status = $request->status;

        $task->save();

        return redirect()->route('tasks.index');
    }

    /**
     * Mark the task as completed.
     */
    public function complete(string $id)
    {
        $task = Task::findOrFail($id);
        $task->status = 'completed';

        $task->save();

        return response()->json([
            'message' => 'Task completed successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}
