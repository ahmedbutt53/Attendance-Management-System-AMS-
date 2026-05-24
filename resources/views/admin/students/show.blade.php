<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }}'s Attendance Profile | AMS Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #09090b;
            --card-bg: rgba(20, 20, 25, 0.6);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #f4f4f5;
            --text-secondary: #a1a1aa;
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --primary-glow: rgba(99, 102, 241, 0.2);
            --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --danger-gradient: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            --font-outfit: 'Outfit', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-outfit);
            background-color: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .bg-blobs {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            overflow: hidden;
            pointer-events: none;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(140px);
            opacity: 0.2;
        }

        .blob-1 {
            width: 600px;
            height: 600px;
            background: #4f46e5;
            top: -10%;
            right: -10%;
        }

        .blob-2 {
            width: 500px;
            height: 500px;
            background: #a855f7;
            bottom: -10%;
            left: -10%;
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: rgba(15, 15, 20, 0.7);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .nav-logo svg {
            color: #6366f1;
        }

        .btn-back {
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 14.5px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateX(-2px);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Toast notifications */
        .toast {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #34d399;
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14.5px;
        }

        /* General validation errors alert */
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 24px;
            font-size: 14.5px;
        }

        .error-list {
            margin-left: 20px;
            margin-top: 5px;
        }

        /* Side-by-Side Layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 30px;
        }

        /* Left Side Panels */
        .left-column {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .profile-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            text-align: center;
        }

        .profile-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(99, 102, 241, 0.5);
            margin: 0 auto 16px auto;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .profile-card h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .profile-card p {
            color: var(--text-secondary);
            font-size: 13.5px;
            margin-bottom: 20px;
        }

        .profile-details {
            text-align: left;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 16px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 13.5px;
        }

        .detail-label {
            color: var(--text-secondary);
        }

        .detail-val {
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
        }

        .form-card h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            background: linear-gradient(to right, #fff, #d4d4d8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 6px;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 10px 14px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: var(--font-outfit);
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.6);
            background: rgba(255, 255, 255, 0.06);
        }

        .btn-submit {
            width: 100%;
            padding: 11px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 8px;
            color: white;
            font-family: var(--font-outfit);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px var(--primary-glow);
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }

        /* Right Column Panels */
        .right-column {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        @media (max-width: 1200px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
        }

        .stat-card h5 {
            color: var(--text-secondary);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .stat-card p {
            font-size: 24px;
            font-weight: 700;
        }

        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
        }

        .panel-header {
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 12px;
        }

        .panel-header h4 {
            font-size: 18px;
            font-weight: 600;
        }

        /* Logs Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .history-table th {
            padding: 12px 14px;
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
        }

        .history-table td {
            padding: 14px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            color: var(--text-secondary);
            vertical-align: middle;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 10.5px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
        }

        .status-badge.present {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .status-badge.absent {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        /* Tiny Table Buttons */
        .btn-table-action {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            color: var(--text-secondary);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-table-action.edit:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #6366f1;
        }

        .btn-table-action.delete:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* Custom Pagination */
        .pagination-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 6px;
        }

        .pagination li a, .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 32px;
            height: 32px;
            padding: 0 8px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13px;
        }

        .pagination li a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: var(--text-primary);
        }

        .pagination li.active span {
            background: var(--primary-gradient);
            color: white;
            border: none;
            box-shadow: 0 4px 10px var(--primary-glow);
        }

        .pagination li.disabled span {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Modal styling */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal {
            background: rgba(20, 20, 25, 0.95);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            width: 90%;
            max-width: 450px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 10px;
        }

        .btn-cancel {
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            cursor: pointer;
            font-family: var(--font-outfit);
            font-weight: 600;
            font-size: 14px;
        }

        .btn-confirm {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-family: var(--font-outfit);
            font-weight: 600;
            font-size: 14px;
            background: var(--primary-gradient);
            box-shadow: 0 4px 10px var(--primary-glow);
        }

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <!-- Admin Navbar -->
    <header class="navbar">
        <div class="nav-logo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 28px; height: 28px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
            <span>AMS Admin Portal</span>
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Directory
        </a>
    </header>

    <main class="container">
        <!-- Toast Notification -->
        @if(session('success'))
            <div class="toast">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- General validation errors alert -->
        @if ($errors->any())
            <div class="alert-danger">
                <strong>Validation failed:</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="dashboard-grid">
            
            <!-- Left Column: Profile Card and Form -->
            <div class="left-column">
                <div class="profile-card">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" class="profile-avatar" alt="Avatar">
                    @else
                        <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($user->name) }}" class="profile-avatar" alt="Avatar">
                    @endif
                    <h3>{{ $user->name }}</h3>
                    <p>Student Profile Details</p>

                    <div class="profile-details">
                        <div class="detail-row">
                            <span class="detail-label">Roll / Employee ID</span>
                            <span class="detail-val">{{ $user->employee_id ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Email</span>
                            <span class="detail-val" style="font-size: 12px;">{{ $user->email }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Department</span>
                            <span class="detail-val">{{ $user->department ?? '-' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Phone</span>
                            <span class="detail-val">{{ $user->phone ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Grade Summary Card -->
                <div class="form-card" style="margin-top: 24px; margin-bottom: 24px;">
                    <h4 style="margin-bottom: 15px;">Monthly Grade History</h4>
                    <div class="profile-details" style="display: flex; flex-direction: column; gap: 10px;">
                        @forelse($grades as $grade)
                            <div class="detail-row" style="padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,0.03);">
                                <span class="detail-label" style="font-weight: 500;">
                                    {{ $grade->calculated_date->format('F Y') }}
                                </span>
                                <span class="detail-val">
                                    <span style="font-weight: 700; color: {{ $grade->grade === 'A' ? '#10b981' : ($grade->grade === 'B' ? '#6366f1' : ($grade->grade === 'C' ? '#06b6d4' : ($grade->grade === 'D' ? '#f59e0b' : '#ef4444'))) }};">
                                        Grade {{ $grade->grade }}
                                    </span>
                                    <span style="font-size: 11.5px; color: var(--text-secondary); margin-left: 4px;">
                                        ({{ $grade->present_days }}P / {{ $grade->absent_days }}A / {{ $grade->leave_days }}L)
                                    </span>
                                </span>
                            </div>
                        @empty
                            <div style="text-align: center; color: var(--text-secondary); font-size: 13.5px; padding: 10px 0;">
                                No monthly grades calculated yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Add Attendance Form -->
                <div class="form-card">
                    <h4>Add Attendance Record</h4>
                    <form action="{{ route('admin.students.attendance.store', $user->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="attendance_date">Select Date</label>
                            <input type="date" name="attendance_date" id="attendance_date" class="form-input" max="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="status">Status</label>
                            <select name="status" id="status" class="form-input" required style="background-color: #121217;">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="notes">Notes (Optional)</label>
                            <input type="text" name="notes" id="notes" class="form-input" placeholder="e.g. Added by Admin">
                        </div>

                        <button type="submit" class="btn-submit">Add Record</button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Stats & Table -->
            <div class="right-column">
                <div class="stats-row">
                    <div class="stat-card">
                        <h5>Attendance Rate</h5>
                        <p style="color: #6366f1;">{{ $attendanceRate }}</p>
                    </div>
                    <div class="stat-card">
                        <h5>Present Days</h5>
                        <p style="color: #10b981;">{{ $presentDays }}</p>
                    </div>
                    <div class="stat-card">
                        <h5>Absent Days</h5>
                        <p style="color: #ef4444;">{{ $absentDays }}</p>
                    </div>
                    <div class="stat-card">
                        <h5>Leaves (Approved/Total)</h5>
                        <p style="color: #a855f7;">
                            {{ $approvedLeaves }}
                            <span style="font-size: 14.5px; color: var(--text-secondary); font-weight: 500;">/ {{ $totalLeaves }}</span>
                        </p>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <h4>Attendance History Log</h4>
                    </div>

                    <div class="table-responsive">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th style="text-align: right; width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $record)
                                    <tr>
                                        <td style="font-weight: 500; color: var(--text-primary);">
                                            {{ \Carbon\Carbon::parse($record->attendance_date)->format('l, M d, Y') }}
                                        </td>
                                        <td>
                                            <span class="status-badge {{ $record->status }}">{{ $record->status }}</span>
                                        </td>
                                        <td>{{ $record->notes ?? '-' }}</td>
                                        <td style="text-align: right;">
                                            <div style="display: flex; gap: 4px; justify-content: flex-end;">
                                                <button type="button" class="btn-table-action edit" onclick="openEditModal({{ $record->id }}, '{{ $record->status }}', '{{ $record->notes }}')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 20.04a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                    </svg>
                                                </button>
                                                <form action="{{ route('admin.attendance.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-table-action delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 16px; height: 16px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center; padding: 40px 0; color: var(--text-secondary);">
                                            No attendance logs recorded yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($attendances->hasPages())
                        <div class="pagination-container">
                            {{ $attendances->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </main>

    <!-- Modal Form for Editing Attendance -->
    <div class="modal-overlay" id="edit_modal">
        <div class="modal">
            <div class="modal-header">
                <h3>Edit Attendance Record</h3>
            </div>
            <form id="edit_form" method="POST" action="">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label" for="edit_status">Status</label>
                    <select name="status" id="edit_status" class="form-input" required style="background-color: #121217;">
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="edit_notes">Notes</label>
                    <input type="text" name="notes" id="edit_notes" class="form-input" required>
                </div>

                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-confirm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(recordId, status, notes) {
            const modal = document.getElementById('edit_modal');
            const form = document.getElementById('edit_form');
            const statusSelect = document.getElementById('edit_status');
            const notesInput = document.getElementById('edit_notes');

            form.action = `/admin/attendance/${recordId}`;
            statusSelect.value = status;
            notesInput.value = notes;

            modal.style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('edit_modal').style.display = 'none';
        }
    </script>
</body>
</html>
