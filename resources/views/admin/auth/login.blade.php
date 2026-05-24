@extends('layouts.auth')

@section('title', 'Admin Sign In')

@section('content')
<div class="auth-card" style="border: 1px solid rgba(168, 85, 247, 0.2); box-shadow: 0 15px 35px rgba(168, 85, 247, 0.1);">
    <div class="auth-header">
        <div class="logo-wrapper" style="background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%);">
            <!-- Shield lock representing admin area -->
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 32px; height: 32px; color: white;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
        </div>
        <h1 style="background: linear-gradient(to right, #a855f7, #6366f1); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Admin Portal</h1>
        <p>Secure Administrator Sign In Only</p>
    </div>

    <!-- General validation error alert -->
    @if ($errors->any())
        <div class="alert alert-danger" id="validation-errors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; flex-shrink: 0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <div>
                <strong>Please fix the following:</strong>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Success message alert -->
    @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px; flex-shrink: 0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                {{ session('success') }}
            </div>
        </div>
    @endif

    <form action="{{ route('admin.login') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="email" class="form-label">Admin Email</label>
            <div class="input-wrapper">
                <input type="email" name="email" id="email" class="form-input" placeholder="admin@attendance.com" value="{{ old('email') }}" required autofocus style="border-color: rgba(168, 85, 247, 0.2);">
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-wrapper">
                <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required style="border-color: rgba(168, 85, 247, 0.2);">
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0V10.5m9 0h-9m9 0a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-9a2.25 2.25 0 01-2.25-2.25v-7.5a2.25 2.25 0 012.25-2.25z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="form-row">
            <label class="checkbox-label" for="remember">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                Remember me
            </label>
        </div>

        <button type="submit" class="btn-submit" style="background: linear-gradient(135deg, #a855f7 0%, #6366f1 100%); box-shadow: 0 8px 20px rgba(168, 85, 247, 0.2);">Sign In as Admin</button>
    </form>

    <div class="auth-footer" style="margin-top: 25px;">
        Looking for student portal? <a href="{{ route('login') }}" style="color: #a855f7;">Log in here</a>
    </div>
</div>
@endsection
