<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Attendance Management System</title>
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
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* Panel Card */
        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .panel-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
        }

        .panel-header h2 {
            font-size: 26px;
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

        /* Profile Picture Upload Area */
        .profile-picture-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 35px;
            position: relative;
        }

        .avatar-wrapper {
            position: relative;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid rgba(99, 102, 241, 0.5);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .avatar-wrapper:hover {
            border-color: rgba(168, 85, 247, 0.8);
            transform: scale(1.03);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .avatar-wrapper:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-overlay svg {
            color: white;
            width: 28px;
            height: 28px;
        }

        .avatar-hint {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 10px;
            font-weight: 500;
        }

        /* Form Layout */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-primary);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: var(--font-outfit);
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: rgba(99, 102, 241, 0.6);
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
        }

        .form-input:disabled {
            background: rgba(255, 255, 255, 0.01);
            color: #71717a;
            border-color: rgba(255, 255, 255, 0.04);
            cursor: not-allowed;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: white;
            font-family: var(--font-outfit);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 20px var(--primary-glow);
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        /* Success & Error alerts */
        .toast {
            border-radius: 12px;
            padding: 14px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14.5px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .toast-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #34d399;
        }

        .toast-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
            .navbar {
                padding: 15px 20px;
            }
            .panel {
                padding: 30px 20px;
            }
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
                <h2>Profile Management</h2>
                <p>Upload a profile picture and keep your basic details up to date.</p>
            </div>

            <!-- Toast Success -->
            @if(session('success'))
                <div class="toast toast-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Toast Errors -->
            @if($errors->any())
                <div class="toast toast-error">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Profile Picture Section -->
                <div class="profile-picture-container">
                    <div class="avatar-wrapper" onclick="document.getElementById('profile_picture_input').click()">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" id="avatar_preview" class="avatar-img" alt="Profile Picture">
                        @else
                            <img src="https://api.dicebear.com/7.x/initials/svg?seed={{ urlencode($user->name) }}" id="avatar_preview" class="avatar-img" alt="Avatar">
                        @endif
                        <div class="avatar-overlay">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                            </svg>
                        </div>
                    </div>
                    <span class="avatar-hint">Click photo to upload new picture</span>
                    <input type="file" name="profile_picture" id="profile_picture_input" style="display: none;" accept="image/*">
                </div>

                <!-- Form Details Grid -->
                <div class="form-grid">
                    
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <!-- Email (Disabled) -->
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address (Locked)</label>
                        <input type="email" id="email" class="form-input" value="{{ $user->email }}" disabled>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone', $user->phone) }}" placeholder="e.g. 03001234567">
                    </div>

                    <!-- Date of Birth -->
                    <div class="form-group">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-input" value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}">
                    </div>

                    <!-- Department (Disabled) -->
                    <div class="form-group">
                        <label for="department" class="form-label">Department (Read-Only)</label>
                        <input type="text" id="department" class="form-input" value="{{ $user->department ?? 'Not Assigned' }}" disabled>
                    </div>

                    <!-- Employee ID / Roll Number (Disabled) -->
                    <div class="form-group">
                        <label for="employee_id" class="form-label">Roll / Employee ID (Read-Only)</label>
                        <input type="text" id="employee_id" class="form-input" value="{{ $user->employee_id ?? 'Not Assigned' }}" disabled>
                    </div>

                    <!-- Address -->
                    <div class="form-group full-width">
                        <label for="address" class="form-label">Home Address</label>
                        <input type="text" name="address" id="address" class="form-input" value="{{ old('address', $user->address) }}" placeholder="e.g. Street Address, City, Country">
                    </div>

                </div>

                <button type="submit" class="btn-submit">Save Changes</button>
            </form>
        </div>
    </main>

    <!-- JS script to handle image preview -->
    <script>
        document.getElementById('profile_picture_input').addEventListener('change', function(event) {
            let input = event.target;
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar_preview').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
</body>
</html>
