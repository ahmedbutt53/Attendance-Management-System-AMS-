<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Secure portal to access the Attendance Management System. Manage roles, permissions, attendance, and tasks.">
    <meta name="theme-color" content="#09090b">
    
    <title>@yield('title', 'Portal') | Attendance Management System</title>
    
    <!-- Custom Auth Styling -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>

    <!-- Animated Glass Blobs Background -->
    <div class="bg-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <!-- Main Auth Content Area -->
    <div class="auth-container">
        @yield('content')
    </div>

</body>
</html>
