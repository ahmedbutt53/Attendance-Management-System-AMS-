<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks | Admin Portal</title>
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
            opacity: 0.15;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateX(-2px);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Header Section */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-title h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 6px;
            background: linear-gradient(to right, #ffffff, #a1a1aa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-title p {
            color: var(--text-secondary);
            font-size: 14.5px;
        }

        .btn-create {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-size: 14.5px;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 15px var(--primary-glow);
            transition: all 0.3s ease;
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        /* Panels & Tables */
        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .task-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .task-table th {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .task-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-primary);
            font-size: 14.5px;
            vertical-align: middle;
        }

        .task-table tr:last-child td {
            border-bottom: none;
        }

        .task-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .task-meta {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .status-badge.submitted {
            background: rgba(99, 102, 241, 0.1);
            color: #818cf8;
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .status-badge.completed {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .btn-view {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.15);
            color: #6366f1;
        }

        /* Toast notification */
        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 12px;
            color: #10b981;
            margin-bottom: 24px;
            font-size: 14.5px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 24px;
        }

        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
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
        <a href="{{ route('admin.dashboard') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Dashboard
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

        <div class="header-section">
            <div class="header-title">
                <h2>Manage Assigned Tasks</h2>
                <p>Create rich-text tasks, assign them to students, and review submitted responses.</p>
            </div>
            <a href="{{ route('admin.tasks.create') }}" class="btn-create">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Assign Task
            </a>
        </div>

        <div class="panel">
            <div class="table-responsive">
                <table class="task-table">
                    <thead>
                        <tr>
                            <th>Task Title</th>
                            <th>Assigned Date</th>
                            <th>Due Date</th>
                            <th>Assigned Students</th>
                            <th>Submissions</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $task)
                            <tr>
                                <td>
                                    <div class="task-title">{{ $task->title }}</div>
                                    <div class="task-meta">By: {{ $task->assignedBy->name }}</div>
                                </td>
                                <td>{{ $task->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($task->due_date)
                                        <span style="color: {{ \Carbon\Carbon::parse($task->due_date)->isPast() ? '#ef4444' : 'var(--text-primary)' }}">
                                            {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span style="color: var(--text-secondary);">No deadline</span>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-weight: 600;">{{ $task->total_assigned }}</span>
                                    <span style="color: var(--text-secondary);">Students</span>
                                </td>
                                <td>
                                    <span style="color: #10b981; font-weight: 600;">{{ $task->total_approved }} Approved</span>
                                    <span style="color: var(--text-secondary);">/</span>
                                    <span style="color: #818cf8; font-weight: 600;">{{ $task->total_submitted }} Pending Review</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.tasks.show', $task->id) }}" class="btn-view">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 14px; height: 14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        View Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-secondary); padding: 40px 0;">
                                    No tasks have been assigned yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $tasks->links() }}
            </div>
        </div>
    </main>

</body>
</html>
