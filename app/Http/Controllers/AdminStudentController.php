<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminStudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'Student')
              ->orWhere('name', 'like', '%student%');
        })->withCount([
            'leaves',
            'leaves as approved_leaves_count' => function ($q) {
                $q->where('status', 'approved');
            }
        ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate(15)->withQueryString();

        foreach ($students as $student) {
            $student->updateOrCreateGradeForMonth();
        }

        return view('admin.students.index', compact('students', 'search'));
    }

    /**
     * Display a specific student's attendance summary and history.
     */
    public function show(User $user)
    {
        // Fetch student's attendance records
        $attendances = Attendance::where('user_id', $user->id)
            ->orderBy('attendance_date', 'desc')
            ->paginate(15);

        // Calculate statistics
        $totalDays = Attendance::where('user_id', $user->id)->count();
        $presentDays = Attendance::where('user_id', $user->id)->where('status', 'present')->count();
        $absentDays = Attendance::where('user_id', $user->id)->where('status', 'absent')->count();
        
        $attendanceRate = $totalDays > 0 ? number_format(($presentDays / $totalDays) * 100, 1) . '%' : '0.0%';

        // Leave counts per student
        $totalLeaves = $user->leaves()->count();
        $approvedLeaves = $user->leaves()->where('status', 'approved')->count();
        $pendingLeaves = $user->leaves()->where('status', 'pending')->count();

        // Update grade for current month
        $user->updateOrCreateGradeForMonth();

        // Get all grades history
        $grades = $user->grades()->orderBy('calculated_date', 'desc')->get();

        return view('admin.students.show', compact(
            'user', 
            'attendances', 
            'totalDays', 
            'presentDays', 
            'absentDays', 
            'attendanceRate',
            'totalLeaves',
            'approvedLeaves',
            'pendingLeaves',
            'grades'
        ));
    }

    /**
     * Store a new attendance record for a student.
     */
    public function storeAttendance(Request $request, User $user)
    {
        $validated = $request->validate([
            'attendance_date' => [
                'required',
                'date',
                'before_or_equal:today',
                Rule::unique('attendance')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
            'status' => ['required', Rule::in(['present', 'absent'])],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'attendance_date.unique' => 'An attendance record already exists for this student on this date.',
        ]);

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'attendance_date' => $validated['attendance_date'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? 'Added by Administrator',
        ]);

        // Send WhatsApp notification
        app(\App\Services\WhatsAppService::class)->sendMessage(
            $user->phone,
            "Hello {$user->name}, your attendance for {$attendance->attendance_date} has been marked as " . ucfirst($attendance->status) . " by the administrator."
        );

        // Automatically update grade for the month of this attendance
        $user->updateOrCreateGradeForMonth($attendance->attendance_date);

        return back()->with('success', 'Attendance record added successfully!');
    }

    /**
     * Update an attendance record.
     */
    public function updateAttendance(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['present', 'absent'])],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $attendance->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'],
        ]);

        // Send WhatsApp notification
        app(\App\Services\WhatsAppService::class)->sendMessage(
            $attendance->user->phone,
            "Hello {$attendance->user->name}, your attendance for {$attendance->attendance_date} has been updated to " . ucfirst($attendance->status) . " by the administrator."
        );

        // Automatically update grade for the month of this attendance
        $attendance->user->updateOrCreateGradeForMonth($attendance->attendance_date);

        return back()->with('success', 'Attendance record updated successfully!');
    }

    /**
     * Delete an attendance record.
     */
    public function destroyAttendance(Attendance $attendance)
    {
        $user = $attendance->user;
        $date = $attendance->attendance_date;
        $attendance->delete();

        // Automatically update grade for the month of this attendance
        $user->updateOrCreateGradeForMonth($date);

        return back()->with('success', 'Attendance record deleted successfully!');
    }
}
