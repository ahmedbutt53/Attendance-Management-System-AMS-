<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks assigned to the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        $tasks = $user->tasks()
            ->with(['responses' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->orderBy('due_date', 'asc')
            ->paginate(15);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Display the specified task detail.
     */
    public function show(Task $task)
    {
        $user = Auth::user();

        // Security check: Ensure the task is actually assigned to this user
        if (!$task->users->contains($user->id)) {
            abort(403, 'Unauthorized action.');
        }

        $response = TaskResponse::where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->first();

        return view('tasks.show', compact('task', 'response'));
    }

    /**
     * Submit response to the task.
     */
    public function submit(Request $request, Task $task)
    {
        $user = Auth::user();

        // Security check
        if (!$task->users->contains($user->id)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'response' => ['required', 'string', 'min:10'],
        ]);

        // Check if an existing response exists and verify its status
        $existingResponse = TaskResponse::where('task_id', $task->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingResponse && $existingResponse->status === 'approved') {
            return back()->with('error', 'This task has already been approved and cannot be modified.');
        }

        // Upsert the response, resetting review status on resubmission
        TaskResponse::updateOrCreate(
            [
                'task_id' => $task->id,
                'user_id' => $user->id,
            ],
            [
                'response' => $validated['response'],
                'status' => 'submitted',
                'feedback' => null,
                'reviewed_by' => null,
                'reviewed_at' => null,
            ]
        );

        return redirect()->route('tasks.index')->with('success', 'Task response submitted successfully!');
    }
}
