<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminLeaveController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminStudentController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminTaskController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Admin Authentication Routes (custom redirects handled in controller)
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        $hasMarkedToday = \App\Models\Attendance::where('user_id', $user->id)
            ->where('attendance_date', \Carbon\Carbon::today()->toDateString())
            ->exists();
            
        $recentAttendances = \App\Models\Attendance::where('user_id', $user->id)
            ->orderBy('attendance_date', 'desc')
            ->take(5)
            ->get();
            
        $recentLeaves = \App\Models\Leave::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $totalDays = \App\Models\Attendance::where('user_id', $user->id)->count();
        $presentDays = \App\Models\Attendance::where('user_id', $user->id)->where('status', 'present')->count();
        $attendanceRate = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) . '%' : '0.0%';
        
        $pendingLeavesCount = \App\Models\Leave::where('user_id', $user->id)->where('status', 'pending')->count();
        
        // Calculate and fetch current grade and active tasks count
        $currentGrade = null;
        $activeTasksCount = 0;
        if ($user->roles->contains('name', 'Student')) {
            $user->updateOrCreateGradeForMonth();
            $currentGrade = $user->grades()
                ->whereDate('calculated_date', \Carbon\Carbon::today()->startOfMonth())
                ->first();

            $activeTasksCount = $user->tasks()
                ->whereNotExists(function ($query) use ($user) {
                    $query->select(\DB::raw(1))
                        ->from('task_responses')
                        ->whereColumn('task_responses.task_id', 'tasks.id')
                        ->where('task_responses.user_id', $user->id)
                        ->where('task_responses.status', 'approved');
                })
                ->count();
        }
        
        return view('dashboard', compact(
            'hasMarkedToday', 
            'recentAttendances', 
            'recentLeaves', 
            'attendanceRate', 
            'pendingLeavesCount',
            'currentGrade',
            'activeTasksCount'
        ));
    })->name('dashboard');

    // Attendance routes
    Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');

    // Leave routes
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves/apply', [LeaveController::class, 'store'])->name('leaves.apply');

    // Tasks routes
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/submit', [TaskController::class, 'submit'])->name('tasks.submit');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminLeaveController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/leaves/{leave}/approve', [AdminLeaveController::class, 'approve'])->name('admin.leaves.approve');
        Route::post('/admin/leaves/{leave}/reject', [AdminLeaveController::class, 'reject'])->name('admin.leaves.reject');

        // Student Management
        Route::get('/admin/students', [AdminStudentController::class, 'index'])->name('admin.students.index');
        Route::get('/admin/students/{user}', [AdminStudentController::class, 'show'])->name('admin.students.show');
        Route::post('/admin/students/{user}/attendance', [AdminStudentController::class, 'storeAttendance'])->name('admin.students.attendance.store');
        Route::put('/admin/attendance/{attendance}', [AdminStudentController::class, 'updateAttendance'])->name('admin.attendance.update');
        Route::delete('/admin/attendance/{attendance}', [AdminStudentController::class, 'destroyAttendance'])->name('admin.attendance.destroy');

        // Task Management
        Route::get('/admin/tasks', [AdminTaskController::class, 'index'])->name('admin.tasks.index');
        Route::get('/admin/tasks/create', [AdminTaskController::class, 'create'])->name('admin.tasks.create');
        Route::post('/admin/tasks', [AdminTaskController::class, 'store'])->name('admin.tasks.store');
        Route::get('/admin/tasks/{task}', [AdminTaskController::class, 'show'])->name('admin.tasks.show');
        Route::post('/admin/task-responses/{response}/review', [AdminTaskController::class, 'review'])->name('admin.tasks.review');

        // Reports
        Route::get('/admin/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/admin/reports/student', [AdminReportController::class, 'studentReport'])->name('admin.reports.student');
        Route::get('/admin/reports/system', [AdminReportController::class, 'systemReport'])->name('admin.reports.system');

        // Roles & Permissions Management
        Route::get('/admin/roles', [AdminRoleController::class, 'index'])->name('admin.roles.index');
        Route::post('/admin/roles', [AdminRoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/admin/roles/{role}/edit', [AdminRoleController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/admin/roles/{role}', [AdminRoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/admin/roles/{role}', [AdminRoleController::class, 'destroy'])->name('admin.roles.destroy');
        Route::post('/admin/users/{user}/roles', [AdminRoleController::class, 'assignUserRoles'])->name('admin.users.roles.assign');
        // WhatsApp test route
        Route::get('/admin/whatsapp-test', function () {
            $to = request('to', '03001234567');
            $message = request('message', 'Hello! This is a test WhatsApp notification from the Attendance Management System.');
            
            $success = app(\App\Services\WhatsAppService::class)->sendMessage($to, $message);
            
            return response()->json([
                'success' => $success,
                'recipient' => $to,
                'message' => $message,
                'provider' => config('services.whatsapp.provider'),
                'log_file' => storage_path('logs/whatsapp.log'),
            ]);
        });
    });
});

