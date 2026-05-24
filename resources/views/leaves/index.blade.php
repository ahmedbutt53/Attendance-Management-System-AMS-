<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests History | Attendance Management System</title>
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
            opacity: 0.25;
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
        }

        .panel-header {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
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

        /* Table */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 20px;
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
            font-size: 11.5px;
            letter-spacing: 0.8px;
        }

        .history-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            color: var(--text-secondary);
            vertical-align: top;
        }

        .history-table tr:hover td {
            background: rgba(255, 255, 255, 0.01);
            color: var(--text-primary);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.12);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .status-badge.approved {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .status-badge.rejected {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        /* Custom Pagination */
        .pagination-container {
            margin-top: 30px;
            display: flex;
            justify-content: center;
        }

        .pagination {
            display: flex;
            list-style: none;
            gap: 8px;
        }

        .pagination li a, .pagination li span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination li a:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            color: var(--text-primary);
        }

        .pagination li.active span {
            background: var(--primary-gradient);
            color: white;
            border: none;
            box-shadow: 0 5px 12px var(--primary-glow);
        }

        .pagination li.disabled span {
            opacity: 0.4;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 15px 20px;
            }
            .panel {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <header class="navbar">
        <a href="{{ route('dashboard') }}" class="nav-logo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 28px; height: 28px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
            <span>AMS Portal</span>
        </a>
        <a href="{{ route('dashboard') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Dashboard
        </a>
    </header>

    <main class="container">
        <div class="panel">
            <div class="panel-header">
                <h2>Leave Requests History</h2>
                <p>Complete record of leaves requested, their statuses, and responses from administration.</p>
            </div>

            <div class="table-responsive">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Total Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Admin Comments</th>
                            <th>Requested On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td style="font-weight: 500; color: var(--text-primary);">
                                    {{ $leave->from_date->format('D, M d, Y') }}
                                </td>
                                <td style="font-weight: 500; color: var(--text-primary);">
                                    {{ $leave->to_date->format('D, M d, Y') }}
                                </td>
                                <td style="text-align: center;">
                                    {{ $leave->from_date->diffInDays($leave->to_date) + 1 }}
                                </td>
                                <td style="max-width: 250px; word-wrap: break-word;">
                                    {{ $leave->reason }}
                                </td>
                                <td>
                                    <span class="status-badge {{ $leave->status }}">
                                        {{ $leave->status }}
                                    </span>
                                </td>
                                <td style="max-width: 200px; font-style: italic; color: #d4d4d8;">
                                    {{ $leave->admin_comments ?? '-' }}
                                </td>
                                <td>
                                    {{ $leave->created_at->format('M d, Y h:i A') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px 0; color: var(--text-secondary);">
                                    No leave requests submitted yet. Use "Request Leave" on the dashboard!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leaves->hasPages())
                <div class="pagination-container">
                    {{ $leaves->links() }}
                </div>
            @endif
        </div>
    </main>

</body>
</html>
