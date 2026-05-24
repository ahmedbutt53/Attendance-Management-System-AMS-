<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Reports | AMS Portal</title>
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

        .header-section {
            margin-bottom: 40px;
            text-align: center;
        }

        .header-section h2 {
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(to right, #fff, #d4d4d8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .header-section p {
            color: var(--text-secondary);
            font-size: 15px;
        }

        /* Forms Layout */
        .reports-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .report-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 15px;
        }

        .icon-wrapper {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.25);
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-wrapper.system {
            background: rgba(168, 85, 247, 0.1);
            border: 1px solid rgba(168, 85, 247, 0.25);
            color: #a855f7;
        }

        .card-header h3 {
            font-size: 19px;
            font-weight: 600;
        }

        .card-header p {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 2px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13.5px;
            color: var(--text-secondary);
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: var(--font-outfit);
            font-size: 14.5px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.6);
            background: rgba(255, 255, 255, 0.06);
        }

                select.form-input {
            background-color: #0f0f13;
            color: var(--text-primary);
        }

        select.form-input option {
            color: #000000;
        }


        .btn-generate {
            width: 100%;
            padding: 13px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: white;
            font-family: var(--font-outfit);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px var(--primary-glow);
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-generate:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }

        /* Validation display */
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
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

        @media (max-width: 768px) {
            .reports-grid {
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
            <nav style="display: flex; gap: 24px;">
                <a href="{{ route('admin.dashboard') }}" style="color: var(--text-secondary); text-decoration: none; font-weight: 500; font-size: 14.5px; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">Leaves</a>
                <a href="{{ route('admin.students.index') }}" style="color: var(--text-secondary); text-decoration: none; font-weight: 500; font-size: 14.5px; transition: color 0.3s;" onmouseover="this.style.color='var(--text-primary)'" onmouseout="this.style.color='var(--text-secondary)'">Students</a>
                <a href="{{ route('admin.reports.index') }}" style="color: var(--text-primary); text-decoration: none; font-weight: 600; font-size: 14.5px; border-bottom: 2px solid #6366f1; padding-bottom: 4px;">Reports</a>
            </nav>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Dashboard
        </a>
    </header>

    <main class="container">
        <div class="header-section">
            <h2>Attendance & Leave Reports</h2>
            <p>Generate analytical summaries and records per student or system-wide between specific dates.</p>
        </div>

        @if($errors->any())
            <div class="alert-danger">
                <strong>Please fix the errors below:</strong>
                <ul class="error-list">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="reports-grid">
            
            <!-- Card 1: Per Student Report -->
            <div class="report-card">
                <form action="{{ route('admin.reports.student') }}" method="GET">
                    <div class="card-header">
                        <div class="icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <h3>Per-Student Report</h3>
                            <p>Detailed history logs and counts for a specific student</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="user_id">Select Student</label>
                        <select name="user_id" id="user_id" class="form-input" required>
                            <option value="">-- Choose Student --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->employee_id ?? 'No Roll ID' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="student_from_date">From Date</label>
                        <input type="date" name="from_date" id="student_from_date" class="form-input" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 25px;">
                        <label class="form-label" for="student_to_date">To Date</label>
                        <input type="date" name="to_date" id="student_to_date" class="form-input" required>
                    </div>

                    <button type="submit" class="btn-generate">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 12.408l-3.285-3.285m0 0a.75.75 0 010-1.06l3.285-3.285m-3.285 3.285h16.2" />
                        </svg>
                        Generate Student Report
                    </button>
                </form>
            </div>

            <!-- Card 2: System-wide Report -->
            <div class="report-card">
                <form action="{{ route('admin.reports.system') }}" method="GET">
                    <div class="card-header">
                        <div class="icon-wrapper system">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 24px; height: 24px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.97 5.97 0 00-.75-2.985m-.058-2.054a3.75 3.75 0 11-4.758-4.758M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3>System-wide Report</h3>
                            <p>Global aggregates and summary tables across all students</p>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 15px;">
                        <label class="form-label" for="system_from_date">From Date</label>
                        <input type="date" name="from_date" id="system_from_date" class="form-input" required>
                    </div>

                    <div class="form-group" style="margin-bottom: 84px;">
                        <label class="form-label" for="system_to_date">To Date</label>
                        <input type="date" name="to_date" id="system_to_date" class="form-input" required>
                    </div>

                    <button type="submit" class="btn-generate" style="background: linear-gradient(135deg, #a855f7 0%, #d946ef 100%); box-shadow: 0 4px 15px rgba(168, 85, 247, 0.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        Generate System Report
                    </button>
                </form>
            </div>

        </div>
    </main>

    <script>
        // Make sure from_date change sets min limit on to_date for both forms
        document.getElementById('student_from_date').addEventListener('change', function() {
            document.getElementById('student_to_date').min = this.value;
        });
        document.getElementById('system_from_date').addEventListener('change', function() {
            document.getElementById('system_to_date').min = this.value;
        });
    </script>
</body>
</html>
