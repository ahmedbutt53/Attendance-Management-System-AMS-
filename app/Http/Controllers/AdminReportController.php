<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    /**
     * Display the reports selection page.
     */
    public function index()
    {
        $students = User::whereHas('roles', function ($q) {
            $q->where('name', 'Student')
              ->orWhere('name', 'like', '%student%');
        })->orderBy('name', 'asc')->get();

        return view('admin.reports.index', compact('students'));
    }

    /**
     * Generate detailed attendance report for a specific student.
     */
    public function studentReport(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
        ]);

        $student = User::findOrFail($request->user_id);
        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $carbonFrom = Carbon::parse($fromDate);
        $carbonTo = Carbon::parse($toDate);

        // Fetch attendance logs within range
        $attendances = Attendance::where('user_id', $student->id)
            ->whereBetween('attendance_date', [$fromDate, $toDate])
            ->orderBy('attendance_date', 'asc')
            ->get();

        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();

        // Calculate overlapping approved leave days in range
        $approvedLeaves = Leave::where('user_id', $student->id)
            ->where('status', 'approved')
            ->where('from_date', '<=', $toDate)
            ->where('to_date', '>=', $fromDate)
            ->get();

        $leaveDaysCount = 0;
        foreach ($approvedLeaves as $leave) {
            $overlapStart = $leave->from_date->greaterThan($carbonFrom) ? $leave->from_date : $carbonFrom;
            $overlapEnd = $leave->to_date->lessThan($carbonTo) ? $leave->to_date : $carbonTo;
            $leaveDaysCount += $overlapStart->diffInDays($overlapEnd) + 1;
        }

        $totalActiveDays = $presentDays + $absentDays;
        $attendanceRate = $totalActiveDays > 0 ? number_format(($presentDays / $totalActiveDays) * 100, 1) . '%' : '0.0%';

        return view('admin.reports.student', compact(
            'student',
            'attendances',
            'fromDate',
            'toDate',
            'presentDays',
            'absentDays',
            'leaveDaysCount',
            'attendanceRate'
        ));
    }

    /**
     * Generate system-wide attendance report.
     */
    public function systemReport(Request $request)
    {
        $request->validate([
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
        ]);

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $carbonFrom = Carbon::parse($fromDate);
        $carbonTo = Carbon::parse($toDate);

        $students = User::whereHas('roles', function ($q) {
            $q->where('name', 'Student')
              ->orWhere('name', 'like', '%student%');
        })->orderBy('name', 'asc')->get();

        // Fetch all attendance logs in range, grouped by user_id
        $allAttendances = Attendance::whereBetween('attendance_date', [$fromDate, $toDate])
            ->get()
            ->groupBy('user_id');

        // Fetch all approved leaves in range, grouped by user_id
        $allLeaves = Leave::where('status', 'approved')
            ->where('from_date', '<=', $toDate)
            ->where('to_date', '>=', $fromDate)
            ->get()
            ->groupBy('user_id');

        $reportData = [];
        $totalSystemPresents = 0;
        $totalSystemAbsents = 0;
        $totalSystemLeaves = 0;

        foreach ($students as $student) {
            $studentAttendances = $allAttendances->get($student->id, collect());
            $presents = $studentAttendances->where('status', 'present')->count();
            $absents = $studentAttendances->where('status', 'absent')->count();

            $studentLeaves = $allLeaves->get($student->id, collect());
            $leaveDays = 0;
            foreach ($studentLeaves as $leave) {
                $overlapStart = $leave->from_date->greaterThan($carbonFrom) ? $leave->from_date : $carbonFrom;
                $overlapEnd = $leave->to_date->lessThan($carbonTo) ? $leave->to_date : $carbonTo;
                $leaveDays += $overlapStart->diffInDays($overlapEnd) + 1;
            }

            $totalActiveDays = $presents + $absents;
            $rate = $totalActiveDays > 0 ? number_format(($presents / $totalActiveDays) * 100, 1) . '%' : '0.0%';

            $reportData[] = [
                'student' => $student,
                'present_count' => $presents,
                'absent_count' => $absents,
                'leave_count' => $leaveDays,
                'rate' => $rate,
            ];

            $totalSystemPresents += $presents;
            $totalSystemAbsents += $absents;
            $totalSystemLeaves += $leaveDays;
        }

        $totalActiveSystemDays = $totalSystemPresents + $totalSystemAbsents;
        $systemAttendanceRate = $totalActiveSystemDays > 0 ? number_format(($totalSystemPresents / $totalActiveSystemDays) * 100, 1) . '%' : '0.0%';

        return view('admin.reports.system', compact(
            'reportData',
            'fromDate',
            'toDate',
            'totalSystemPresents',
            'totalSystemAbsents',
            'totalSystemLeaves',
            'systemAttendanceRate'
        ));
    }
}
