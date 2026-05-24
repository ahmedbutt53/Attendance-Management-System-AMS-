<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | AMS Portal</title>
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

        .nav-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .btn-logout {
            padding: 10px 18px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 10px;
            color: #f87171;
            cursor: pointer;
            font-family: var(--font-outfit);
            font-size: 14.5px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
            transform: translateY(-1px);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-info h4 {
            color: var(--text-secondary);
            font-size: 13.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .stat-info p {
            font-size: 28px;
            font-weight: 700;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon.pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .stat-icon.approved { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .stat-icon.rejected { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

        /* Filter Controls */
        .filter-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
        }

        .filter-tab {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-tab:hover {
            background: rgba(255, 255, 255, 0.06);
            color: var(--text-primary);
        }

        .filter-tab.active {
            background: var(--primary-gradient);
            color: white;
            border: none;
            box-shadow: 0 4px 10px var(--primary-glow);
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
            font-size: 11.5px;
            letter-spacing: 0.8px;
        }

        .history-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            color: var(--text-secondary);
            vertical-align: middle;
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

        /* Action Buttons */
        .btn-action {
            padding: 8px 14px;
            border: none;
            border-radius: 8px;
            font-family: var(--font-outfit);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: white;
            text-decoration: none;
        }

        .btn-action.approve {
            background: var(--success-gradient);
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        }

        .btn-action.reject {
            background: var(--danger-gradient);
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
        }

        .btn-action:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
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
            max-width: 480px;
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
            font-size: 20px;
            font-weight: 600;
        }

        .modal-input {
            width: 100%;
            padding: 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: var(--font-outfit);
            font-size: 14.5px;
            margin-bottom: 20px;
            resize: none;
            height: 100px;
        }

        .modal-input:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.6);
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
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
        }

        .btn-confirm.approve { background: var(--success-gradient); }
        .btn-confirm.reject { background: var(--danger-gradient); }

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

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
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
        <div style="display: flex; align-items: center; gap: 45px;">
            <a href="{{ route('admin.dashboard') }}" class="nav-logo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 28px; height: 28px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
                <span>AMS Admin Portal</span>
            </a>
            <nav style="display: flex; gap: 20px;">
                <a href="{{ route('admin.dashboard') }}" style="color: var(--text-primary); text-decoration: none; font-weight: 600; font-size: 14.5px;">Dashboard</a>
                <a href="{{ route('admin.students.index') }}" style="color: var(--text-secondary); text-decoration: none; font-weight: 500; font-size: 14.5px; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">Student Directory</a>
                <a href="{{ route('admin.reports.index') }}" style="color: var(--text-secondary); text-decoration: none; font-weight: 500; font-size: 14.5px; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">Reports</a>
                <a href="{{ route('admin.tasks.index') }}" style="color: var(--text-secondary); text-decoration: none; font-weight: 500; font-size: 14.5px; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">Tasks</a>
                <a href="{{ route('admin.roles.index') }}" style="color: var(--text-secondary); text-decoration: none; font-weight: 500; font-size: 14.5px; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">Roles & Permissions</a>
            </nav>
        </div>
        <div class="nav-user">
            <span style="font-weight: 500;">Admin Mode</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
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

        <!-- Stats Section -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Pending Leaves</h4>
                    <p>{{ $totalPending }}</p>
                </div>
                <div class="stat-icon pending">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Approved Leaves</h4>
                    <p>{{ $totalApproved }}</p>
                </div>
                <div class="stat-icon approved">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h4>Rejected Leaves</h4>
                    <p>{{ $totalRejected }}</p>
                </div>
                <div class="stat-icon rejected">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 24px; height: 24px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Filter and Table Container -->
        <div class="filter-bar">
            <div class="filter-tabs">
                <a href="{{ route('admin.dashboard', ['status' => 'all']) }}" class="filter-tab {{ $status == 'all' ? 'active' : '' }}">All Requests</a>
                <a href="{{ route('admin.dashboard', ['status' => 'pending']) }}" class="filter-tab {{ $status == 'pending' ? 'active' : '' }}">Pending Only</a>
                <a href="{{ route('admin.dashboard', ['status' => 'approved']) }}" class="filter-tab {{ $status == 'approved' ? 'active' : '' }}">Approved Only</a>
                <a href="{{ route('admin.dashboard', ['status' => 'rejected']) }}" class="filter-tab {{ $status == 'rejected' ? 'active' : '' }}">Rejected Only</a>
            </div>
        </div>

        <div class="panel">
            <div class="table-responsive">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Actions / Feedback</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-primary);">{{ $leave->user->name }}</div>
                                    <div style="font-size: 12px; color: var(--text-secondary);">
                                        {{ $leave->user->department ?? 'General' }} | {{ $leave->user->employee_id ?? 'No Roll ID' }}
                                    </div>
                                </td>
                                <td>{{ $leave->from_date->format('M d, Y') }}</td>
                                <td>{{ $leave->to_date->format('M d, Y') }}</td>
                                <td>{{ $leave->from_date->diffInDays($leave->to_date) + 1 }}</td>
                                <td style="max-width: 250px; word-wrap: break-word;">{{ $leave->reason }}</td>
                                <td>
                                    <span class="status-badge {{ $leave->status }}">{{ $leave->status }}</span>
                                </td>
                                <td>
                                    @if($leave->status === 'pending')
                                        <div style="display: flex; gap: 8px;">
                                            <button type="button" class="btn-action approve" onclick="openActionModal('approve', {{ $leave->id }})">
                                                Approve
                                            </button>
                                            <button type="button" class="btn-action reject" onclick="openActionModal('reject', {{ $leave->id }})">
                                                Reject
                                            </button>
                                        </div>
                                    @else
                                        <div style="font-size: 13.5px;">
                                            <strong style="color: var(--text-primary);">Comments:</strong> 
                                            <span style="font-style: italic;">{{ $leave->admin_comments ?? 'No comment provided' }}</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px 0; color: var(--text-secondary);">
                                    No leave requests found for status: "{{ $status }}".
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Form for Action and Comments -->
    <div class="modal-overlay" id="action_modal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="modal_title">Review Leave Request</h3>
            </div>
            <form id="action_form" method="POST" action="">
                @csrf
                <label class="form-label" style="display: block; margin-bottom: 8px; font-size: 14px;">Admin Comments (Optional)</label>
                <textarea name="admin_comments" class="modal-input" placeholder="Enter comments or reason for your decision..."></textarea>
                <div class="modal-buttons">
                    <button type="button" class="btn-cancel" onclick="closeActionModal()">Cancel</button>
                    <button type="submit" class="btn-confirm" id="btn_confirm">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openActionModal(action, leaveId) {
            const modal = document.getElementById('action_modal');
            const form = document.getElementById('action_form');
            const title = document.getElementById('modal_title');
            const btn = document.getElementById('btn_confirm');

            form.action = `/admin/leaves/${leaveId}/${action}`;
            
            if (action === 'approve') {
                title.textContent = 'Approve Leave Request';
                btn.textContent = 'Approve';
                btn.className = 'btn-confirm approve';
            } else {
                title.textContent = 'Reject Leave Request';
                btn.textContent = 'Reject';
                btn.className = 'btn-confirm reject';
            }

            modal.style.display = 'flex';
        }

        function closeActionModal() {
            document.getElementById('action_modal').style.display = 'none';
        }
    </script>
</body>
</html>
