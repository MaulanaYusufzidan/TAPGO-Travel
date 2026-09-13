@extends('layouts.app')

@section('title', 'Traveler Information — TAPGO TRAVEL')

@section('content')
<div class="container py-5">
    <h1 class="fw-bold mb-1">Traveler Information</h1>
    <p class="text-muted mb-4">
        {{ $schedule->trip->title }} &middot; {{ $schedule->date->translatedFormat('d M Y') }} &middot; {{ $quantity }} traveler
    </p>

    <form method="POST" action="{{ route('bookings.store') }}">
        @csrf
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

        @for ($i = 0; $i < $quantity; $i++)
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h2 class="h6 fw-semibold text-uppercase mb-3">Traveler {{ $i + 1 }}</h2>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Nama Lengkap</label>
                            <input type="text" name="travelers[{{ $i }}][full_name]" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Jenis Kelamin</label>
                            <select name="travelers[{{ $i }}][gender]" class="form-select">
                                <option value="">-</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Tanggal Lahir</label>
                            <input type="date" name="travelers[{{ $i }}][date_of_birth]" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">No. HP</label>
                            <input type="text" name="travelers[{{ $i }}][phone]" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Email</label>
                            <input type="email" name="travelers[{{ $i }}][email]" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        @endfor

        <button type="submit" class="btn btn-primary">Lanjutkan</button>
    </form>
</div>
@endsection
