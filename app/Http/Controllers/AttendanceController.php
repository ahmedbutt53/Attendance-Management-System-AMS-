<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the user's attendance.
     */
    public function index()
    {
        $attendances = Attendance::where('user_id', Auth::id())
            ->orderBy('attendance_date', 'desc')
            ->paginate(15);

        return view('attendance.index', compact('attendances'));
    }

    /**
     * Mark the user's attendance for today.
     */
    public function mark(Request $request)
    {
        $user = Auth::user();

        // Security check: Only Students can self-mark attendance
        if (!$user->roles->contains(fn($role) => str_contains(strtolower($role->name), 'student'))) {
            return back()->with('error', 'Only students are allowed to self-mark attendance.');
        }

        $today = Carbon::today()->toDateString();

        // Check if attendance already marked for today
        $alreadyMarked = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->exists();

        if ($alreadyMarked) {
            return back()->with('error', 'You have already marked your attendance for today.');
        }

        // Mark attendance
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'attendance_date' => $today,
            'status' => 'present',
            'notes' => 'Self-marked from dashboard',
        ]);

        // Recalculate grade
        $user->updateOrCreateGradeForMonth($attendance->attendance_date);

        return back()->with('success', 'Attendance marked successfully for today!');
    }
}
