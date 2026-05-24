<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role | Admin Portal</title>
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
            --input-bg: rgba(20, 20, 25, 0.9);
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

        .blob-1 { width: 600px; height: 600px; background: #4f46e5; top: -10%; right: -10%; }
        .blob-2 { width: 500px; height: 500px; background: #a855f7; bottom: -10%; left: -10%; }

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

        .nav-logo svg { color: #6366f1; }

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
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .panel-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 24px;
            color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            font-family: var(--font-outfit);
            font-size: 14.5px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        /* Grouped Permissions checklist */
        .permission-groups-container {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-height: 450px;
            overflow-y: auto;
        }

        .permission-group {
            margin-bottom: 20px;
        }

        .permission-group:last-child {
            margin-bottom: 0;
        }

        .group-header {
            font-size: 12.5px;
            font-weight: 700;
            color: #818cf8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            border-bottom: 1px dotted var(--border-color);
            padding-bottom: 4px;
        }

        .permission-checkbox-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .permission-checkbox-item:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .permission-checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #6366f1;
            cursor: pointer;
        }

        .permission-label {
            font-size: 14px;
            color: var(--text-primary);
            cursor: pointer;
            text-transform: capitalize;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-family: var(--font-outfit);
            font-size: 15.5px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px var(--primary-glow);
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        .error-message {
            color: #f87171;
            font-size: 13px;
            margin-top: 6px;
        }

        .warning-banner {
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            line-height: 1.5;
            margin-bottom: 24px;
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
        <a href="{{ route('admin.roles.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 16px; height: 16px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Roles
        </a>
    </header>

    <main class="container">
        <div class="panel">
            <div class="panel-title">Edit Role: {{ $role->name }}</div>

            @if(in_array($role->name, ['Admin', 'Student']))
                <div class="warning-banner">
                    <strong>System Role:</strong> The name of this role is locked because it is essential for core application logic. However, you can still edit its description and permissions configuration.
                </div>
            @endif

            <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Role Name -->
                <div class="form-group">
                    <label class="form-label" for="name">Role Name</label>
                    <input type="text" id="name" name="name" class="form-input" 
                           value="{{ old('name', $role->name) }}" 
                           {{ in_array($role->name, ['Admin', 'Student']) ? 'disabled' : '' }} required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label class="form-label" for="description">Description</label>
                    <input type="text" id="description" name="description" class="form-input" value="{{ old('description', $role->description) }}">
                    @error('description')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Permissions Checklist -->
                <div class="form-group">
                    <label class="form-label">Update Role Permissions</label>
                    
                    @if($role->name === 'Admin')
                        <div class="warning-banner" style="margin-bottom: 12px; padding: 10px;">
                            The Administrator role is granted all permissions by default to prevent administrative lockout.
                        </div>
                    @endif

                    <div class="permission-groups-container">
                        
                        <!-- Group: Attendance -->
                        <div class="permission-group">
                            <div class="group-header">Attendance Management</div>
                            @foreach($permissions->filter(fn($p) => str_contains($p->name, 'attendance')) as $p)
                                <label class="permission-checkbox-item">
                                    <input type="checkbox" name="permissions[]" value="{{ $p->id }}" 
                                           {{ in_array($p->id, $rolePermissions) || $role->name === 'Admin' ? 'checked' : '' }}
                                           {{ $role->name === 'Admin' ? 'disabled' : '' }}>
                                    <span class="permission-label">{{ str_replace('_', ' ', $p->name) }}</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Group: Leaves -->
                        <div class="permission-group">
                            <div class="group-header">Leave Management</div>
                            @foreach($permissions->filter(fn($p) => str_contains($p->name, 'leave')) as $p)
                                <label class="permission-checkbox-item">
                                    <input type="checkbox" name="permissions[]" value="{{ $p->id }}"
                                           {{ in_array($p->id, $rolePermissions) || $role->name === 'Admin' ? 'checked' : '' }}
                                           {{ $role->name === 'Admin' ? 'disabled' : '' }}>
                                    <span class="permission-label">{{ str_replace('_', ' ', $p->name) }}</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Group: Tasks -->
                        <div class="permission-group">
                            <div class="group-header">Task Management</div>
                            @foreach($permissions->filter(fn($p) => str_contains($p->name, 'task')) as $p)
                                <label class="permission-checkbox-item">
                                    <input type="checkbox" name="permissions[]" value="{{ $p->id }}"
                                           {{ in_array($p->id, $rolePermissions) || $role->name === 'Admin' ? 'checked' : '' }}
                                           {{ $role->name === 'Admin' ? 'disabled' : '' }}>
                                    <span class="permission-label">{{ str_replace('_', ' ', $p->name) }}</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Group: Roles & Permissions -->
                        <div class="permission-group">
                            <div class="group-header">Roles & Permissions</div>
                            @foreach($permissions->filter(fn($p) => str_contains($p->name, 'role') || str_contains($p->name, 'permission')) as $p)
                                <label class="permission-checkbox-item">
                                    <input type="checkbox" name="permissions[]" value="{{ $p->id }}"
                                           {{ in_array($p->id, $rolePermissions) || $role->name === 'Admin' ? 'checked' : '' }}
                                           {{ $role->name === 'Admin' ? 'disabled' : '' }}>
                                    <span class="permission-label">{{ str_replace('_', ' ', $p->name) }}</span>
                                </label>
                            @endforeach
                        </div>

                        <!-- Group: Other System -->
                        <div class="permission-group">
                            <div class="group-header">System Features & Reports</div>
                            @foreach($permissions->filter(fn($p) => !str_contains($p->name, 'attendance') && !str_contains($p->name, 'leave') && !str_contains($p->name, 'task') && !str_contains($p->name, 'role') && !str_contains($p->name, 'permission')) as $p)
                                <label class="permission-checkbox-item">
                                    <input type="checkbox" name="permissions[]" value="{{ $p->id }}"
                                           {{ in_array($p->id, $rolePermissions) || $role->name === 'Admin' ? 'checked' : '' }}
                                           {{ $role->name === 'Admin' ? 'disabled' : '' }}>
                                    <span class="permission-label">{{ str_replace('_', ' ', $p->name) }}</span>
                                </label>
                            @endforeach
                        </div>

                    </div>
                </div>

                <button type="submit" class="btn-submit">Save Role Changes</button>
            </form>
        </div>
    </main>

</body>
</html>
