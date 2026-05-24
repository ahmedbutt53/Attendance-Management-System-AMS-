@extends('layouts.auth')

@section('title', 'Sign Up')

@section('content')
<div class="auth-card" style="max-width: 550px; margin: 0 auto;">
    <div class="auth-header">
        <div class="logo-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 32px; height: 32px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-9-3.75h.008v.008H3.75V3.75zm.008 4.5H3.75v.008h.008V8.25zm0 4.5H3.75v.008h.008v-.008zm0 4.5H3.75v.008h.008v-.008zm0 4.5H3.75v.008h.008v-.008zm16.5-9a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <h1>Create Account</h1>
        <p>Register a new student account to get started</p>
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

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Full Name</label>
            <div class="input-wrapper">
                <input type="text" name="name" id="name" class="form-input" placeholder="John Doe" value="{{ old('name') }}" required autofocus>
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-wrapper">
                <input type="email" name="email" id="email" class="form-input" placeholder="john.doe@university.edu" value="{{ old('email') }}" required>
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Two columns for secondary info -->
        <div class="form-grid">
            <div class="form-group">
                <label for="phone" class="form-label">Phone Number (Optional)</label>
                <div class="input-wrapper">
                    <input type="text" name="phone" id="phone" class="form-input" style="padding-left: 40px;" placeholder="03001234567" value="{{ old('phone') }}">
                    <div class="input-icon" style="left: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.824-1.802-5.196-4.178-7-7l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="department" class="form-label">Department (Optional)</label>
                <div class="input-wrapper">
                    <input type="text" name="department" id="department" class="form-input" style="padding-left: 40px;" placeholder="Computer Science" value="{{ old('department') }}">
                    <div class="input-icon" style="left: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 18px; height: 18px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.9c4.956 0 9.31-1.766 12.23-4.406a60.412 60.412 0 00-.492-6.347m-21.57 3a59.782 59.782 0 001.37-3.21a59.756 59.756 0 002.13-1.578m18.07 4.788a59.76 59.76 0 01-1.37-3.21a59.766 59.766 0 01-2.13-1.578m-14.57 4h14.57M12 3v1m0 16v1m9-9h-1M3 12H2m16.5-6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="employee_id" class="form-label">Employee / Roll ID (Optional)</label>
            <div class="input-wrapper">
                <input type="text" name="employee_id" id="employee_id" class="form-input" placeholder="CS-2026-001" value="{{ old('employee_id') }}">
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0017.25 4.5H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm3-10.5h.008v.008H7.5V9zm0 3h.008v.008H7.5v-.008zm0 3h.008v.008H7.5v-.008z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-wrapper">
                <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0V10.5m9 0h-9m9 0a2.25 2.25 0 012.25 2.25v7.5a2.25 2.25 0 01-2.25 2.25h-9a2.25 2.25 0 01-2.25-2.25v-7.5a2.25 2.25 0 012.25-2.25z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <div class="input-wrapper">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="••••••••" required>
                <div class="input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit" style="margin-top: 10px;">Register</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="{{ route('login') }}">Sign In</a>
    </div>
</div>
@endsection
