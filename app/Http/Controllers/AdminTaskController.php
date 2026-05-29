<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index()
    {
        $tasks = Task::with('assignedBy')
            ->withCount('users as total_assigned')
            ->withCount(['responses as total_submitted' => function ($q) {
                $q->where('status', 'submitted');
            }])
            ->withCount(['responses as total_approved' => function ($q) {
                $q->where('status', 'approved');
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create()
    {
        $students = User::whereHas('roles', function ($q) {
            $q->where('name', 'Student')
              ->orWhere('name', 'like', '%student%');
        })->get();

        return view('admin.tasks.create', compact('students'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
            'students' => ['required', 'array', 'min:1'],
            'students.*' => ['required', 'exists:users,id'],
        ]);

        $task = Task::create([
            'assigned_by' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
            'status' => 'pending',
        ]);

        $task->users()->attach($validated['students']);

        // Send WhatsApp notifications to assigned students
        $students = User::whereIn('id', $validated['students'])->get();
        foreach ($students as $student) {
            if ($student->phone) {
                $dueDateStr = $task->due_date ? $task->due_date->format('Y-m-d') : 'No due date';
                app(\App\Services\WhatsAppService::class)->sendMessage(
                    $student->phone,
                    "Hello {$student->name}, a new task '{$task->title}' has been assigned to you by the administrator. Due date: {$dueDateStr}."
                );
            }
        }

        return redirect()->route('admin.tasks.index')->with('success', 'Task assigned successfully!');
    }

    /**
     * Display the specified task and its assignments/responses.
     */
    public function show(Task $task)
    {
        $task->load(['assignedBy', 'users']);

        // Fetch all responses for this task
        $responses = TaskResponse::with('user')
            ->where('task_id', $task->id)
            ->get()
            ->keyBy('user_id');

        return view('admin.tasks.show', compact('task', 'responses'));
    }

    /**
     * Review (Approve or Reject) a student's task submission.
     */
    public function review(Request $request, TaskResponse $response)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $response->update([
            'status' => $validated['status'],
            'feedback' => $validated['feedback'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Send WhatsApp notification to student
        $student = $response->user;
        $task = $response->task;
        if ($student && $student->phone) {
            $feedbackStr = $response->feedback ? " Feedback: {$response->feedback}" : "";
            app(\App\Services\WhatsAppService::class)->sendMessage(
                $student->phone,
                "Hello {$student->name}, your response for task '{$task->title}' has been " . strtoupper($response->status) . " by the administrator.{$feedbackStr}"
            );
        }

        return back()->with('success', 'Task response reviewed and status updated successfully!');
    }
}
