@extends('layouts.app')

@section('title', 'Lupa Password — TAPGO TRAVEL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h4 fw-bold mb-3 text-center">Lupa Password</h1>
                    <p class="text-muted small text-center mb-4">
                        Masukkan email kamu, kami akan kirim link untuk reset password.
                    </p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}" required autofocus>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">Kirim Link Reset</button>

                        <p class="text-center small mb-0">
                            <a href="{{ route('login') }}">&larr; Kembali ke Login</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
