<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Report | AMS Portal</title>
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
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Panel Card */
        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .panel-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .panel-header h2 {
            font-size: 24px;
            font-weight: 600;
            background: linear-gradient(to right, #fff, #d4d4d8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .panel-header p {
            color: var(--text-secondary);
            font-size: 14.5px;
            margin-top: 4px;
        }

        /* Stats Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 18px;
            text-align: center;
        }

        .stat-card h5 {
            font-size: 12px;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .stat-card p {
            font-size: 26px;
            font-weight: 700;
        }

        /* Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14.5px;
        }

        .history-table th {
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
        }

        .history-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            color: var(--text-secondary);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
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

        .print-btn {
            padding: 10px 18px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: white;
            font-family: var(--font-outfit);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px var(--primary-glow);
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }

        /* Profile Block */
        .profile-summary {
            display: flex;
            align-items: center;
            gap: 20px;
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .profile-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(99, 102, 241, 0.4);
        }

        .profile-info h3 {
            font-size: 19px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .profile-info p {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .profile-info span {
            display: inline-block;
            margin-right: 15px;
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }
            .profile-summary {
                flex-direction: column;
                text-align: center;
            }
            .navbar {
                padding: 15px 20px;
            }
        }

        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .navbar, .btn-back, .print-btn, .bg-blobs {
                display: none !important;
            }
            .panel {
                border: none !important;
                background: none !important;
                box-shadow: none !important;
                padding: 0 !important;
            }
            .stat-card {
                border: 1px solid #e4e4e7 !important;
                color: black !important;
            }
            .stat-card h5 {
                color: #52525b !important;
            }
            .stat-card p {
                color: black !important;
            }
            .history-table th {
                background: #f4f4f5 !important;
                color: black !important;
                border-bottom: 2px solid #e4e4e7 !important;
            }
            .history-table td {
                color: black !important;
                border-bottom: 1px solid #e4e4e7 !important;
            }
            .status-badge {
                border: 1px solid #a1a1aa !important;
                color: black !important;
                background: none !important;
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
        <a href="{{ route('admin.reports.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Reports
        </a>
    </header>

    <main class="container">
        <div class="panel">
            <div class="panel-header">
                <div>
                    <h2>Student Attendance Report</h2>
                    <p>Generated for: <strong>{{ \Carbon\Carbon::parse($fromDate)->format('M d, Y') }}</strong> to <strong>{{ \Carbon\Carbon::parse($toDate)->format('M d, Y') }}</strong></p>
                </div>
                <button onclick="window.print()" class="print-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.82l-.24-.24c-1.216-1.216-3.189-1.216-4.405 0A3.11 3.11 0 001.1 16.5c0 1.258.825 2.32 1.975 2.684A3.746 3.746 0 006 21c1.268 0 2.39-.63 3.068-1.593a3.746 3.746 0 003.296-1.043 3.745 3.745 0 001.043-3.296A3.745 3.745 0 0012 12c-1.268 0-2.39.63-3.068 1.593a3.746 3.746 0 00-3.296 1.043 3.746 3.746 0 00-1.043-3.296A3.745 3.745 0 006 9c0 1.268.63 2.39 1.593 3.068" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M12 3v2.25m5.25-2.25v2.25M3 18.75h18M6.75 15h10.5a2.25 2.25 0 002.25-2.25V9a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 9v3.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Print Report
                </button>
            </div>

            <!-- Profile Summary -->
            <div class="profile-summary">
                @if($student->profile_picture)
                    <img src="{{ asset('storage/' . $student->profile_picture) }}" class="profile-img" alt="Avatar">
                @else
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($student->name) }}" class="profile-img" alt="Avatar">
                @endif
                <div class="profile-info">
                    <h3>{{ $student->name }}</h3>
                    <p>
                        <span><strong>Roll ID:</strong> {{ $student->employee_id ?? 'Not Assigned' }}</span>
                        <span><strong>Email:</strong> {{ $student->email }}</span>
                        <span><strong>Dept:</strong> {{ $student->department ?? 'General' }}</span>
                    </p>
                </div>
            </div>

            <!-- Stats -->
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
                    <h5>Approved Leaves</h5>
                    <p style="color: #a855f7;">{{ $leaveDaysCount }}</p>
                </div>
            </div>

            <div class="panel-header" style="margin-top: 20px; border-bottom: none; padding-bottom: 0;">
                <h3 style="font-size: 18px; font-weight: 600;">Detailed Logs</h3>
            </div>

            <div class="table-responsive">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Marked Time</th>
                            <th>Notes / Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $record)
                            <tr>
                                <td style="font-weight: 500; color: var(--text-primary);">
                                    {{ $record->attendance_date->format('l, F d, Y') }}
                                </td>
                                <td>
                                    <span class="status-badge {{ $record->status }}">
                                        {{ $record->status }}
                                    </span>
                                </td>
                                <td>
                                    {{ $record->status === 'present' ? $record->updated_at->timezone('Asia/Karachi')->format('h:i A') : '-' }}
                                </td>
                                <td>
                                    {{ $record->notes ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 40px 0; color: var(--text-secondary);">
                                    No attendance records found within this date range.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>
