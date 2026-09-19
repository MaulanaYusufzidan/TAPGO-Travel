@extends('layouts.app')

@section('title', 'Join Us — TAPGO TRAVEL')
@section('meta_description', 'Buat akun TAPGO TRAVEL dan mulai rencanakan perjalananmu.')

@section('content')
<div class="auth-split">
    <div class="auth-split__art">
        <img src="https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=1200&q=80" alt="Traveler looking at mountain view">
        <div class="overlay">
            <h2>Join thousands of travelers discovering Indonesia with TAPGO.</h2>
        </div>
    </div>
    <div class="auth-split__form">
        <div style="max-width: 420px; width: 100%;">
            <h1 class="h3 fw-bold mb-1">Create your account</h1>
            <p class="text-muted mb-4">Join us and start planning your next trip.</p>

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <div class="input-icon">
                        <span aria-hidden="true">👤</span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Your full name" required autofocus>
                    </div>
                    @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <div class="input-icon">
                        <span aria-hidden="true">✉️</span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="you@example.com" required>
                    </div>
                    @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-icon">
                        <span aria-hidden="true">🔒</span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="At least 8 characters" required>
                    </div>
                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-icon">
                        <span aria-hidden="true">🔒</span>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat your password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3">Create Account</button>

                <p class="text-center small text-muted mb-0">
                    Already have an account? <a href="{{ route('login') }}" class="fw-semibold">Sign in here</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
