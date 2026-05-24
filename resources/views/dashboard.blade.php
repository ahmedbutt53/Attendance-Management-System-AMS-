<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Attendance Management System</title>
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
            --success-glow: rgba(16, 185, 129, 0.15);
            --info-gradient: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            --info-glow: rgba(6, 182, 212, 0.15);
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

        /* Background blur blobs */
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
        }

        .nav-logo svg {
            color: #6366f1;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 15px;
            color: var(--text-primary);
        }

        .user-role {
            font-size: 12px;
            color: #a855f7;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-logout {
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: var(--font-outfit);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.4);
            color: #ef4444;
        }

        /* Container */
        .dashboard-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .welcome-text h2 {
            font-size: 26px;
            margin-bottom: 8px;
            background: linear-gradient(to right, #fff, #d4d4d8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .welcome-text p {
            color: var(--text-secondary);
            font-size: 15px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 56px;
            height: 56px;
            border-radius: 14px;
            color: white;
        }

        .stat-icon.attendance {
            background: var(--success-gradient);
            box-shadow: 0 8px 16px var(--success-glow);
        }

        .stat-icon.leaves {
            background: var(--info-gradient);
            box-shadow: 0 8px 16px var(--info-glow);
        }

        .stat-icon.tasks {
            background: var(--primary-gradient);
            box-shadow: 0 8px 16px var(--primary-glow);
        }

        .stat-details h3 {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 4px;
        }

        .stat-details p {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Content Sections */
        .dashboard-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 12px;
        }

        .panel-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        .activity-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-info h4 {
            font-size: 14.5px;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .activity-info p {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.present {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-badge.absent {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.15);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-badge.approved {
            background: rgba(16, 185, 129, 0.15);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-badge.rejected {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        /* Success & Error alerts */
        .toast-success {
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
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .toast-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14.5px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: rgba(20, 20, 25, 0.95);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            animation: modalSlide 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalSlide {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding-bottom: 12px;
        }

        .modal-header h3 {
            font-size: 20px;
            font-weight: 600;
        }

        .close-btn {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 24px;
            cursor: pointer;
            line-height: 1;
        }

        .close-btn:hover {
            color: var(--text-primary);
        }

        /* Modal Forms */
        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            font-size: 13.5px;
            color: var(--text-primary);
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
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
        }

        .btn-modal-submit {
            width: 100%;
            padding: 12px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: white;
            font-family: var(--font-outfit);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.2);
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-modal-submit:hover {
            transform: translateY(-1px);
            filter: brightness(1.1);
        }
    </style>
</head>
<body>

    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <!-- Navigation Header -->
    <header class="navbar">
        <div class="nav-logo">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 28px; height: 28px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
            <span>AMS Portal</span>
        </div>
        <div class="nav-user">
            <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: inherit;">
                @if(Auth::user()->profile_picture)
                    <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(99, 102, 241, 0.4);" alt="Avatar">
                @else
                    <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode(Auth::user()->name) }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(99, 102, 241, 0.4);" alt="Avatar">
                @endif
                <div class="user-info" style="text-align: left; display: block;">
                    <div class="user-name" style="margin-bottom: 2px;">{{ Auth::user()->name }}</div>
                    <div class="user-role">
                        @foreach(Auth::user()->roles as $role)
                            {{ $role->name }}
                        @endforeach
                    </div>
                </div>
            </a>
            
            <a href="{{ route('profile.edit') }}" class="btn-logout" style="text-decoration: none; line-height: 1;">Profile</a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout" style="line-height: 1;">Logout</button>
            </form>
        </div>
    </header>

    <!-- Main Container -->
    <main class="dashboard-container">

        <!-- Banner Alert Success -->
        @if(session('success'))
            <div class="toast-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Banner Alert Error -->
        @if(session('error') || $errors->any())
            <div class="toast-error" style="flex-direction: column; align-items: flex-start; gap: 8px; padding: 16px 20px; height: auto;">
                <div style="display: flex; align-items: center; gap: 12px; width: 100%;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 20px; height: 20px; flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <span>
                        @if(session('error'))
                            {{ session('error') }}
                        @else
                            Validation failed. Please verify your inputs:
                        @endif
                    </span>
                </div>
                @if($errors->any())
                    <ul style="margin-left: 36px; font-size: 13.5px; text-align: left; list-style-type: disc;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif

        <!-- Welcome Banner -->
        <section class="welcome-banner">
            <div class="welcome-text">
                <h2>Hello, {{ Auth::user()->name }}</h2>
                <p>Welcome to your portal. Here is a quick snapshot of your attendance and tasks today.</p>
            </div>
            <div style="font-size: 14px; background: rgba(255,255,255,0.05); padding: 10px 20px; border-radius: 10px; border: 1px solid var(--border-color);">
                <strong>Dept:</strong> {{ Auth::user()->department ?? 'Not Assigned' }}
            </div>
        </section>

        <!-- Quick Actions Panel -->
        <section class="quick-actions" style="margin-bottom: 30px;">
            <div class="panel" style="display: flex; gap: 20px; flex-wrap: wrap; align-items: center; justify-content: space-between;">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <h3 style="font-size: 18px; font-weight: 600;">Quick Actions</h3>
                    <p style="font-size: 13.5px; color: var(--text-secondary);">Manage your attendance and leave requests</p>
                </div>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    
                    <!-- Mark Attendance Button -->
                    @if($hasMarkedToday)
                        <button class="btn-action success" disabled style="padding: 12px 24px; background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; border-radius: 10px; font-family: var(--font-outfit); font-weight: 600; cursor: not-allowed; display: inline-flex; align-items: center; gap: 8px;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 18px; height: 18px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                            Attendance Marked
                        </button>
                    @else
                        <form action="{{ route('attendance.mark') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-action" style="padding: 12px 24px; background: var(--primary-gradient); border: none; color: white; border-radius: 10px; font-family: var(--font-outfit); font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 5px 15px var(--primary-glow); display: inline-flex; align-items: center; gap: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Mark Attendance
                            </button>
                        </form>
                    @endif

                    <!-- Request Leave Button -->
                    <button onclick="openModal()" class="btn-action" style="padding: 12px 24px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 10px; font-family: var(--font-outfit); font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m-3-12h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                        Request Leave
                    </button>

                    <!-- View Attendance Button -->
                    <a href="{{ route('attendance.index') }}" class="btn-action" style="text-decoration: none; padding: 12px 24px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 10px; font-family: var(--font-outfit); font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        View Attendance
                    </a>

                    <!-- View Tasks Button -->
                    <a href="{{ route('tasks.index') }}" class="btn-action" style="text-decoration: none; padding: 12px 24px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 10px; font-family: var(--font-outfit); font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 12.408l-3.285-3.285m0 0a.75.75 0 010-1.06l3.285-3.285m-3.285 3.285h16.2" />
                        </svg>
                        View Tasks
                    </a>
                </div>
            </div>
        </section>

        <!-- Stats Grid -->
        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon attendance">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 28px; height: 28px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 12.408l-3.285-3.285m0 0a.75.75 0 010-1.06l3.285-3.285m-3.285 3.285h16.2" />
                    </svg>
                </div>
                <div class="stat-details">
                    <h3>Attendance Rate</h3>
                    <p>{{ $attendanceRate }}</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon leaves">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 28px; height: 28px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                    </svg>
                </div>
                <div class="stat-details">
                    <h3>Pending Leaves</h3>
                    <p>{{ $pendingLeavesCount }} Requests</p>
                </div>
            </div>

            <a href="{{ route('tasks.index') }}" class="stat-card" style="text-decoration: none; cursor: pointer; display: flex;">
                <div class="stat-icon tasks">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 28px; height: 28px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5" />
                    </svg>
                </div>
                <div class="stat-details">
                    <h3 style="color: var(--text-secondary);">Tasks</h3>
                    <p style="color: var(--text-primary);">{{ $activeTasksCount ?? 0 }} Active</p>
                </div>
            </a>

            @if(Auth::user()->roles->contains('name', 'Student'))
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #a855f7 0%, #d946ef 100%); box-shadow: 0 8px 16px rgba(168, 85, 247, 0.15); display: flex; align-items: center; justify-content: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 28px; height: 28px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                </div>
                <div class="stat-details">
                    <h3>Current Month Grade</h3>
                    <p style="color: #d946ef; font-weight: 800;">
                        Grade {{ $currentGrade->grade ?? 'F' }} 
                        <span style="font-size: 13.5px; font-weight: 500; color: var(--text-secondary); margin-left: 6px;">
                            ({{ $currentGrade->present_days ?? 0 }} Presents)
                        </span>
                    </p>
                </div>
            </div>
            @endif
        </section>

        <!-- Main Dashboard Split Content -->
        <section class="dashboard-content">
            <!-- Left Panel: Recent Attendance -->
            <div class="panel">
                <div class="panel-header">
                    <h3>Recent Attendance Logs</h3>
                    <a href="{{ route('attendance.index') }}" style="color: #6366f1; text-decoration: none; font-size: 13.5px; font-weight: 500;">View All</a>
                </div>
                @forelse($recentAttendances as $attendance)
                    <div class="activity-item">
                        <div class="activity-info">
                            <h4>Self Marked Attendance</h4>
                            <p>{{ $attendance->attendance_date->format('l, M d, Y') }}</p>
                        </div>
                        <span class="status-badge {{ $attendance->status }}">{{ $attendance->status }}</span>
                    </div>
                @empty
                    <div style="text-align: center; padding: 30px 0; color: var(--text-secondary); font-size: 14.5px;">
                        No recent attendance records.
                    </div>
                @endforelse
            </div>

            <!-- Right Panel: Leave Requests -->
            <div class="panel">
                <div class="panel-header">
                    <h3>Leave Requests</h3>
                    <a href="{{ route('leaves.index') }}" style="color: #6366f1; text-decoration: none; font-size: 13.5px; font-weight: 500;">View All</a>
                </div>
                @forelse($recentLeaves as $leave)
                    <div class="activity-item">
                        <div class="activity-info">
                            <h4>Leave ({{ $leave->from_date->format('M d') }} - {{ $leave->to_date->format('M d') }})</h4>
                            <p style="max-width: 180px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                Reason: {{ $leave->reason }}
                            </p>
                        </div>
                        <span class="status-badge {{ $leave->status }}">{{ $leave->status }}</span>
                    </div>
                @empty
                    <div style="text-align: center; padding: 30px 0; color: var(--text-secondary); font-size: 14.5px;">
                        No leave requests submitted yet.
                    </div>
                @endforelse
            </div>
        </section>

    </main>

    <!-- Request Leave Modal -->
    <div id="leaveModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Submit Leave Request</h3>
                <button onclick="closeModal()" class="close-btn">&times;</button>
            </div>
            
            <form action="{{ route('leaves.apply') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-input" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-input" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="reason" class="form-label">Reason for Leave</label>
                    <textarea name="reason" id="reason" class="form-input" style="height: 120px; resize: none;" placeholder="Provide a detailed explanation..." required></textarea>
                </div>

                <button type="submit" class="btn-modal-submit">Submit Leave Request</button>
            </form>
        </div>
    </div>

    <!-- Script for Modal Operations -->
    <script>
        function openModal() {
            document.getElementById('leaveModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('leaveModal').style.display = 'none';
        }

        // Close when clicking outside of modal
        window.onclick = function(event) {
            let modal = document.getElementById('leaveModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Dynamic min date rules for leave dates
        document.getElementById('from_date').addEventListener('change', function() {
            let fromDateVal = this.value;
            let toDateField = document.getElementById('to_date');
            toDateField.min = fromDateVal;
            if (toDateField.value && toDateField.value < fromDateVal) {
                toDateField.value = fromDateVal;
            }
        });
    </script>
</body>
</html>
