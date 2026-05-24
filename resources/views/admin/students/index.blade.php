<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management | AMS Portal</title>
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
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Search Header */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .header-title h2 {
            font-size: 26px;
            font-weight: 600;
            background: linear-gradient(to right, #fff, #d4d4d8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-title p {
            color: var(--text-secondary);
            font-size: 14.5px;
            margin-top: 4px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            max-width: 400px;
            width: 100%;
        }

        .search-input {
            flex-grow: 1;
            padding: 10px 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: var(--font-outfit);
            font-size: 14.5px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.6);
            background: rgba(255, 255, 255, 0.06);
        }

        .btn-search {
            padding: 10px 20px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: white;
            font-family: var(--font-outfit);
            font-size: 14.5px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px var(--primary-glow);
            transition: all 0.3s ease;
        }

        .btn-search:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
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

        .student-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14.5px;
        }

        .student-table th {
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.02);
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 1px solid var(--border-color);
            text-transform: uppercase;
            font-size: 11.5px;
            letter-spacing: 0.8px;
        }

        .student-table td {
            padding: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            color: var(--text-secondary);
            vertical-align: middle;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(99, 102, 241, 0.4);
        }

        .btn-view {
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-view:hover {
            background: var(--primary-gradient);
            border-color: transparent;
            color: white;
            box-shadow: 0 4px 10px var(--primary-glow);
            transform: translateY(-1px);
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
            .header-section {
                flex-direction: column;
                align-items: flex-start;
            }
            .search-form {
                max-width: 100%;
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
        <!-- Header & Search -->
        <div class="header-section">
            <div class="header-title">
                <h2>Student Directory</h2>
                <p>Manage registered students, view their attendance rates, and manage logs.</p>
            </div>
            <form action="{{ route('admin.students.index') }}" method="GET" class="search-form">
                <input type="text" name="search" class="search-input" placeholder="Search by Name, Roll ID, Email..." value="{{ $search }}">
                <button type="submit" class="btn-search">Search</button>
            </form>
        </div>

        <div class="panel">
            <div class="table-responsive">
                <table class="student-table">
                    <thead>
                        <tr>
                            <th>Student Details</th>
                            <th>Roll / Employee ID</th>
                            <th>Email Address</th>
                            <th>Department</th>
                            <th>Current Grade</th>
                            <th>Leaves (Approved/Total)</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="student-info">
                                        @if($student->profile_picture)
                                            <img src="{{ asset('storage/' . $student->profile_picture) }}" class="student-avatar" alt="Avatar">
                                        @else
                                            <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($student->name) }}" class="student-avatar" alt="Avatar">
                                        @endif
                                        <div style="font-weight: 600; color: var(--text-primary);">{{ $student->name }}</div>
                                    </div>
                                </td>
                                <td>{{ $student->employee_id ?? '-' }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->department ?? '-' }}</td>
                                <td>
                                    @php
                                        $currentGradeRecord = $student->grades()
                                            ->whereDate('calculated_date', \Carbon\Carbon::today()->startOfMonth())
                                            ->first();
                                        $gradeValue = $currentGradeRecord->grade ?? 'F';
                                    @endphp
                                    <span style="font-weight: 700; color: {{ $gradeValue === 'A' ? '#10b981' : ($gradeValue === 'B' ? '#6366f1' : ($gradeValue === 'C' ? '#06b6d4' : ($gradeValue === 'D' ? '#f59e0b' : '#ef4444'))) }};">
                                        Grade {{ $gradeValue }}
                                    </span>
                                </td>
                                <td style="font-weight: 500;">
                                    <span style="color: #10b981;">{{ $student->approved_leaves_count }}</span>
                                    <span style="color: var(--text-secondary);">/</span>
                                    <span style="color: var(--text-primary);">{{ $student->leaves_count }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.students.show', $student->id) }}" class="btn-view">
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
                                <td colspan="5" style="text-align: center; padding: 40px 0; color: var(--text-secondary);">
                                    No registered students found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="pagination-container">
                    {{ $students->links() }}
                </div>
            @endif
        </div>
    </main>

</body>
</html>
