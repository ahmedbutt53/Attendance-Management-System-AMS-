<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLeaveController extends Controller
{
    /**
     * Show the admin dashboard/leaves panel.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Leave::with('user')->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $leaves = $query->paginate(15)->withQueryString();

        // Calculate statistics for the admin dashboard cards
        $totalPending = Leave::where('status', 'pending')->count();
        $totalApproved = Leave::where('status', 'approved')->count();
        $totalRejected = Leave::where('status', 'rejected')->count();

        return view('admin.dashboard', compact('leaves', 'status', 'totalPending', 'totalApproved', 'totalRejected'));
    }

    /**
     * Approve a leave request.
     */
    public function approve(Request $request, Leave $leave)
    {
        $request->validate([
            'admin_comments' => ['nullable', 'string', 'max:500'],
        ]);

        $leave->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'admin_comments' => $request->get('admin_comments'),
        ]);

        // Send WhatsApp notification
        app(\App\Services\WhatsAppService::class)->sendMessage(
            $leave->user->phone,
            "Hello {$leave->user->name}, your leave request from {$leave->from_date->format('Y-m-d')} to {$leave->to_date->format('Y-m-d')} has been APPROVED by the administrator."
        );

        // Recalculate grades for overlapping months
        $leave->user->updateOrCreateGradeForMonth($leave->from_date);
        if (!$leave->from_date->isSameMonth($leave->to_date)) {
            $leave->user->updateOrCreateGradeForMonth($leave->to_date);
        }

        return back()->with('success', 'Leave request approved successfully!');
    }

    /**
     * Reject a leave request.
     */
    public function reject(Request $request, Leave $leave)
    {
        $request->validate([
            'admin_comments' => ['nullable', 'string', 'max:500'],
        ]);

        $leave->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'admin_comments' => $request->get('admin_comments'),
        ]);

        // Send WhatsApp notification
        app(\App\Services\WhatsAppService::class)->sendMessage(
            $leave->user->phone,
            "Hello {$leave->user->name}, your leave request from {$leave->from_date->format('Y-m-d')} to {$leave->to_date->format('Y-m-d')} has been REJECTED by the administrator."
        );

        // Recalculate grades for overlapping months
        $leave->user->updateOrCreateGradeForMonth($leave->from_date);
        if (!$leave->from_date->isSameMonth($leave->to_date)) {
            $leave->user->updateOrCreateGradeForMonth($leave->to_date);
        }

        return back()->with('success', 'Leave request rejected successfully!');
    }
}
