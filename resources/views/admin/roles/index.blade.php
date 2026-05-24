<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles & Permissions | Admin Portal</title>
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
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
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

        .toast.error {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .layout-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        @media (max-width: 992px) {
            .layout-grid {
                grid-template-columns: 1fr;
            }
        }

        .panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .panel-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
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
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }

        /* Grouped Permissions checklist */
        .permission-groups-container {
            max-height: 350px;
            overflow-y: auto;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.2);
            padding: 16px;
        }

        .permission-group {
            margin-bottom: 16px;
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
            margin-bottom: 8px;
            border-bottom: 1px dotted var(--border-color);
            padding-bottom: 4px;
        }

        .permission-checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .permission-checkbox-item:hover {
            background: rgba(255, 255, 255, 0.04);
        }

        .permission-checkbox-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #6366f1;
            cursor: pointer;
        }

        .permission-label {
            font-size: 13.5px;
            color: var(--text-primary);
            cursor: pointer;
            text-transform: capitalize;
        }

        .permission-desc {
            font-size: 11px;
            color: var(--text-secondary);
            margin-left: auto;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary-gradient);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-family: var(--font-outfit);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 15px var(--primary-glow);
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
        }

        /* Roles List Table */
        .roles-table {
            width: 100%;
            border-collapse: collapse;
        }

        .roles-table th {
            padding: 14px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            text-align: left;
        }

        .roles-table td {
            padding: 14px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }

        .roles-table tr:last-child td {
            border-bottom: none;
        }

        .role-name {
            font-weight: 700;
            color: #ffffff;
        }

        .role-desc {
            font-size: 12.5px;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        .permissions-badge-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            max-width: 320px;
        }

        .permission-badge {
            background: rgba(99, 102, 241, 0.08);
            border: 1px solid rgba(99, 102, 241, 0.15);
            color: #a5b4fc;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .btn-edit-small {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-edit-small:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #6366f1;
            border-color: rgba(99, 102, 241, 0.3);
        }

        .btn-delete-small {
            display: inline-flex;
            align-items: center;
            padding: 6px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
            border-radius: 6px;
            color: #f87171;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-delete-small:hover {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
        }

        /* User List Section */
        .user-panel {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .user-header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }

        .search-form {
            display: flex;
            gap: 10px;
            max-width: 350px;
            width: 100%;
        }

        .btn-search {
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            cursor: pointer;
            font-family: var(--font-outfit);
            font-size: 13.5px;
            font-weight: 600;
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table th {
            padding: 14px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            text-align: left;
        }

        .users-table td {
            padding: 14px;
            border-bottom: 1px solid var(--border-color);
            font-size: 14px;
        }

        .role-pill {
            display: inline-flex;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--text-secondary);
        }

        .role-pill.admin { background: rgba(99, 102, 241, 0.1); border-color: rgba(99, 102, 241, 0.2); color: #818cf8; }
        .role-pill.student { background: rgba(16, 185, 129, 0.1); border-color: rgba(16, 185, 129, 0.2); color: #10b981; }

        /* Assign Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 200;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: #121214;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            padding: 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            position: relative;
        }

        .close-modal {
            position: absolute;
            top: 18px;
            right: 18px;
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close-modal:hover { color: #ffffff; }

        .modal-header {
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }

        .modal-header h3 { font-size: 19px; font-weight: 700; }

        .checkbox-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 10px;
            margin-bottom: 20px;
            background: rgba(255,255,255,0.01);
            border: 1px solid var(--border-color);
            padding: 12px;
            border-radius: 10px;
        }

        .checkbox-grid-item {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
        }

        .checkbox-grid-item:hover {
            background: rgba(255,255,255,0.03);
        }

        .checkbox-grid-item input {
            width: 16px;
            height: 16px;
            accent-color: #6366f1;
        }

        .checkbox-grid-item span {
            font-size: 14px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .error-message {
            color: #f87171;
            font-size: 12.5px;
            margin-top: 4px;
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
        @if(session('error'))
            <div class="toast error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="layout-grid">
            <!-- Left Panel: Create Role -->
            <div class="panel">
                <div class="panel-title">Create Custom Role</div>
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label" for="name">Role Name</label>
                        <input type="text" id="name" name="name" class="form-input" placeholder="e.g. Teacher, HR, Supervisor" required value="{{ old('name') }}">
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Description</label>
                        <input type="text" id="description" name="description" class="form-input" placeholder="Role function or details" value="{{ old('description') }}">
                        @error('description')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Set Role Permissions</label>
                        <div class="permission-groups-container">
                            
                            <!-- Group: Attendance -->
                            <div class="permission-group">
                                <div class="group-header">Attendance Management</div>
                                @foreach($permissions->filter(fn($p) => str_contains($p->name, 'attendance')) as $p)
                                    <label class="permission-checkbox-item">
                                        <input type="checkbox" name="permissions[]" value="{{ $p->id }}">
                                        <span class="permission-label">{{ str_replace('_', ' ', $p->name) }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Group: Leaves -->
                            <div class="permission-group">
                                <div class="group-header">Leave Management</div>
                                @foreach($permissions->filter(fn($p) => str_contains($p->name, 'leave')) as $p)
                                    <label class="permission-checkbox-item">
                                        <input type="checkbox" name="permissions[]" value="{{ $p->id }}">
                                        <span class="permission-label">{{ str_replace('_', ' ', $p->name) }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Group: Tasks -->
                            <div class="permission-group">
                                <div class="group-header">Task Management</div>
                                @foreach($permissions->filter(fn($p) => str_contains($p->name, 'task')) as $p)
                                    <label class="permission-checkbox-item">
                                        <input type="checkbox" name="permissions[]" value="{{ $p->id }}">
                                        <span class="permission-label">{{ str_replace('_', ' ', $p->name) }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Group: Roles & Permissions -->
                            <div class="permission-group">
                                <div class="group-header">Roles & Permissions</div>
                                @foreach($permissions->filter(fn($p) => str_contains($p->name, 'role') || str_contains($p->name, 'permission')) as $p)
                                    <label class="permission-checkbox-item">
                                        <input type="checkbox" name="permissions[]" value="{{ $p->id }}">
                                        <span class="permission-label">{{ str_replace('_', ' ', $p->name) }}</span>
                                    </label>
                                @endforeach
                            </div>

                            <!-- Group: Other System -->
                            <div class="permission-group">
                                <div class="group-header">System Features & Reports</div>
                                @foreach($permissions->filter(fn($p) => !str_contains($p->name, 'attendance') && !str_contains($p->name, 'leave') && !str_contains($p->name, 'task') && !str_contains($p->name, 'role') && !str_contains($p->name, 'permission')) as $p)
                                    <label class="permission-checkbox-item">
                                        <input type="checkbox" name="permissions[]" value="{{ $p->id }}">
                                        <span class="permission-label">{{ str_replace('_', ' ', $p->name) }}</span>
                                    </label>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Create Role</button>
                </form>
            </div>

            <!-- Right Panel: Roles list -->
            <div class="panel">
                <div class="panel-title">Defined Roles & Associated Permissions</div>
                <div style="overflow-x: auto;">
                    <table class="roles-table">
                        <thead>
                            <tr>
                                <th>Role Details</th>
                                <th>Permissions</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <span class="role-name">{{ $role->name }}</span>
                                        <div class="role-desc">{{ $role->description ?: 'No description provided.' }}</div>
                                    </td>
                                    <td>
                                        <div class="permissions-badge-list">
                                            @forelse($role->permissions as $perm)
                                                <span class="permission-badge">{{ str_replace('_', ' ', $perm->name) }}</span>
                                            @empty
                                                <span style="color: var(--text-secondary); font-size: 11.5px;">No permissions</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 8px; align-items: center;">
                                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn-edit-small">
                                                Edit
                                            </a>
                                            
                                            @if(!in_array($role->name, ['Admin', 'Student', 'Teacher', 'HR']))
                                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-delete-small">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 14px; height: 14px;">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- User Role Assignment Panel -->
        <div class="user-panel">
            <div class="user-header-section">
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: #ffffff;">Assign Roles to Users</h3>
                    <p style="color: var(--text-secondary); font-size: 13px; margin-top: 2px;">Search and manage user role configurations.</p>
                </div>
                <form action="{{ route('admin.roles.index') }}" method="GET" class="search-form">
                    <input type="text" name="search" class="form-input" placeholder="Search by name, email..." value="{{ $search }}">
                    <button type="submit" class="btn-search">Search</button>
                </form>
            </div>

            <div style="overflow-x: auto;">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>User Details</th>
                            <th>Email Address</th>
                            <th>Active Roles</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div style="font-weight: 600; color: #ffffff;">{{ $user->name }}</div>
                                    @if($user->employee_id)
                                        <div style="font-size: 12px; color: var(--text-secondary);">ID: {{ $user->employee_id }} | {{ $user->department }}</div>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <div style="display: flex; gap: 6px;">
                                        @forelse($user->roles as $role)
                                            <span class="role-pill {{ strtolower($role->name) }}">{{ $role->name }}</span>
                                        @empty
                                            <span style="color: var(--text-secondary); font-size: 12px;">No Role</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    <button class="btn-edit-small" onclick="openAssignModal('{{ $user->id }}', '{{ $user->name }}', {{ json_encode($user->roles->pluck('id')) }})">
                                        Assign Roles
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 30px 0;">
                                    No users found matching the search criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $users->links() }}
            </div>
        </div>
    </main>

    <!-- Assign Roles Modal -->
    <div id="assignModal" class="modal">
        <div class="modal-content">
            <button class="close-modal" onclick="closeAssignModal()">&times;</button>
            <div class="modal-header">
                <h3>Assign Roles</h3>
                <p style="color: var(--text-secondary); font-size: 13.5px; margin-top: 4px;" id="user-modal-name"></p>
            </div>

            <form id="assignForm" method="POST" action="">
                @csrf
                
                <label class="form-label">Select User Roles</label>
                <div class="checkbox-grid">
                    @foreach($roles as $role)
                        <label class="checkbox-grid-item">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" id="modal-role-{{ $role->id }}">
                            <span>{{ $role->name }}</span>
                            <span style="font-size: 11px; color: var(--text-secondary); margin-left: auto;">{{ $role->description }}</span>
                        </label>
                    @endforeach
                </div>

                <button type="submit" class="btn-submit">Save Role Assignments</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('assignModal');

        function openAssignModal(userId, userName, userRoleIds) {
            document.getElementById('user-modal-name').innerText = "Configure roles for: " + userName;
            
            // Set action URL
            document.getElementById('assignForm').action = "/admin/users/" + userId + "/roles";

            // Uncheck all first
            const checkboxes = document.querySelectorAll('#assignForm input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);

            // Check current user roles
            userRoleIds.forEach(roleId => {
                const cb = document.getElementById('modal-role-' + roleId);
                if (cb) cb.checked = true;
            });

            modal.style.display = 'flex';
        }

        function closeAssignModal() {
            modal.style.display = 'none';
        }

        // Close on overlay click
        window.onclick = function(event) {
            if (event.target == modal) {
                closeAssignModal();
            }
        }
    </script>

</body>
</html>
