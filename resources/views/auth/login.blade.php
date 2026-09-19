@extends('layouts.app')

@section('title', 'Sign In — TAPGO TRAVEL')
@section('meta_description', 'Masuk ke akun TAPGO TRAVEL Anda.')

@section('content')
<div class="auth-split">
    <div class="auth-split__art">
        <img src="https://images.unsplash.com/photo-1501594907352-04cda38ebc29?auto=format&fit=crop&w=1200&q=80" alt="Aerial view of tropical coastline">
        <div class="overlay">
            <h2>Welcome back — your next journey is waiting.</h2>
        </div>
    </div>
    <div class="auth-split__form">
        <div style="max-width: 420px; width: 100%;">
            <h1 class="h3 fw-bold mb-1">Sign in to TAPGO</h1>
            <p class="text-muted mb-4">Enter your details to access your account.</p>

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-icon">
                        <span aria-hidden="true">✉️</span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
                    </div>
                    @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-icon">
                        <span aria-hidden="true">🔒</span>
                        <input type="password" name="password" class="form-control" placeholder="Your password" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" name="remember" value="1" class="form-check-input" id="remember">
                        <label class="form-check-label small" for="remember">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Sign In</button>

                <p class="text-center small text-muted mb-0">
                    Don't have an account? <a href="{{ route('register') }}" class="fw-semibold">Join us here</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
