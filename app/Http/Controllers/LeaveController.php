<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveController extends Controller
{
    /**
     * Display a listing of the user's leave requests.
     */
    public function index()
    {
        $leaves = Leave::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('leaves.index', compact('leaves'));
    }

    /**
     * Store a newly created leave request in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Security check: Only Students can request leaves
        if (!$user->roles->contains(fn($role) => str_contains(strtolower($role->name), 'student'))) {
            return back()->with('error', 'Only students can request leaves.');
        }

        $validated = $request->validate([
            'from_date' => ['required', 'date', 'after_or_equal:today'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        // Create Leave Request
        Leave::create([
            'user_id' => $user->id,
            'from_date' => $validated['from_date'],
            'to_date' => $validated['to_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Leave request submitted successfully to the administrator.');
    }
}
