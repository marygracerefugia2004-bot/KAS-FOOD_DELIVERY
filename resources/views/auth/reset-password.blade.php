@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')

<style>
    :root {
        --orange: #FF6B2C;
        --orange2: #FF8B00;
        --navy: #0D1B4B;
        --bg: #080D1A;
        --surface: #0F172A;
        --border: #1E2D4A;
        --text: #E2E8F0;
        --muted: #94A3B8;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--bg);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        padding: 1rem;
    }

    .reset-card {
        width: 100%;
        max-width: 440px;
        background: rgba(12, 19, 33, 0.85);
        backdrop-filter: blur(18px);
        border: 1px solid rgba(30, 41, 59, 0.6);
        border-radius: 24px;
        padding: 2.5rem 2rem;
        box-shadow: 0 25px 45px -12px rgba(0, 0, 0, 0.5);
    }

    .reset-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .reset-icon {
        width: 68px;
        height: 68px;
        border-radius: 20px;
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
        margin-bottom: 1.2rem;
        box-shadow: 0 10px 20px -5px rgba(255, 107, 44, 0.3);
    }

    .reset-title {
        font-family: 'Space Grotesk', sans-serif;
        font-size: 1.7rem;
        font-weight: 700;
        color: white;
        letter-spacing: -0.3px;
    }

    .reset-subtitle {
        color: var(--muted);
        margin-top: 0.4rem;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .form-group {
        margin-bottom: 1.4rem;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--muted);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .form-control {
        width: 100%;
        padding: 0.85rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 14px;
        font-size: 0.95rem;
        background: #0f172a;
        color: var(--text);
        transition: all 0.2s ease;
        font-family: 'Plus Jakarta Sans', sans-serif;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--orange);
        box-shadow: 0 0 0 3px rgba(255, 107, 44, 0.2);
    }

    .form-control::placeholder {
        color: #3b455e;
    }

    .password-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--muted);
        font-size: 1rem;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }

    .toggle-password:hover {
        color: var(--orange);
    }

    .btn {
        width: 100%;
        padding: 0.9rem;
        border-radius: 14px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.25s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.6rem;
        border: none;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--orange), var(--orange2));
        color: white;
        box-shadow: 0 6px 14px rgba(255, 107, 44, 0.35);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 22px -8px rgba(255, 107, 44, 0.5);
    }

    .alert {
        padding: 0.75rem 1rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #34D399;
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #F87171;
    }

    .password-hint {
        font-size: 0.75rem;
        color: var(--muted);
        margin-top: 0.3rem;
    }

    .back-link {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 1.5rem;
    }

    .back-link a {
        color: var(--muted);
        font-size: 0.85rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: color 0.2s;
    }

    .back-link a:hover {
        color: white;
    }
</style>

<div class="reset-card">
    <div class="reset-header">
        <div class="reset-icon">
            <i class="fas fa-lock"></i>
        </div>
        <h1 class="reset-title">Set New Password</h1>
        <p class="reset-subtitle">Enter your new password below</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-group">
            <label class="form-label" for="password">New Password</label>
            <div class="password-wrapper">
                <input type="password" id="password" name="password" class="form-control" 
                    placeholder="••••••••" required minlength="8">
                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                    <i class="fas fa-eye-slash" id="eye-password"></i>
                </button>
            </div>
            <p class="password-hint">Minimum 8 characters</p>
        </div>

        <div class="form-group">
            <label class="form-label" for="password_confirmation">Confirm New Password</label>
            <div class="password-wrapper">
                <input type="password" id="password_confirmation" name="password_confirmation" 
                    class="form-control" placeholder="••••••••" required>
                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                    <i class="fas fa-eye-slash" id="eye-password_confirmation"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Reset Password
        </button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}">
            <i class="fas fa-arrow-left"></i> Back to Login
        </a>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById('eye-' + inputId);
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
</script>

@endsection