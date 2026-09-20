@extends('layouts.app')
@section('title','My Profile — TAPGO Travel')
@section('content')
<main class="marketplace-page">
<div class="container">
    <x-breadcrumb current="My Profile" />

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row g-4">
        <aside class="col-lg-3">
            <div class="profile-hero">
                <div class="avatar-fallback">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                <h2>{{ Auth::user()->name }}</h2>
                <p>{{ Auth::user()->email }}</p>
                <p class="small mb-0" style="color: rgba(255,255,255,.7);">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
            </div>

            <div class="profile-nav">
                <a class="active" data-tab="tab-profile" href="#profile">👤 My Profile</a>
                <a data-tab="tab-bookings" href="#bookings">🧳 My Bookings ({{ $bookings->count() }})</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">↪ Sign Out</button></form>
            </div>

            <div class="row g-2 mt-3">
                <div class="col-6"><div class="profile-stat"><strong>{{ $upcomingCount }}</strong><span>Upcoming</span></div></div>
                <div class="col-6"><div class="profile-stat"><strong>{{ $completedCount }}</strong><span>Completed</span></div></div>
            </div>
        </aside>

        <div class="col-lg-9">
            <div id="tab-profile" class="profile-tab-pane">
                @if ($nextBooking)
                    <section class="detail-panel">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <p class="eyebrow">Upcoming booking</p>
                                <h2 class="h4 mb-1">{{ $nextBooking->schedule->trip->title }}</h2>
                                <p class="text-muted mb-0">📅 {{ $nextBooking->schedule->date->translatedFormat('d M Y') }} · {{ $nextBooking->quantity }} traveler(s) · {{ ucfirst($nextBooking->status) }}</p>
                            </div>
                            <a href="{{ route('trips.show', $nextBooking->schedule->trip) }}" class="btn btn-outline-primary">View trip</a>
                        </div>
                    </section>
                @else
                    <section class="detail-panel">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <p class="eyebrow">Upcoming booking</p>
                                <h2 class="h4">Nothing booked just yet</h2>
                                <p class="text-muted mb-0">Your confirmed journeys will appear here.</p>
                            </div>
                            <a href="{{ route('trips.index') }}" class="btn btn-primary">Explore trips</a>
                        </div>
                    </section>
                @endif

                <section class="detail-panel">
                    <h2>Personal Information</h2>
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf @method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Full Name</label>
                                <input name="name" value="{{ old('name', Auth::user()->name) }}" class="form-control @error('name') is-invalid @enderror">
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Email</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="form-control @error('email') is-invalid @enderror">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                    </form>
                </section>

                <section class="detail-panel mb-0">
                    <h2>Update Password</h2>
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf @method('PATCH')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small">Current Password</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-primary mt-3">Update Password</button>
                    </form>
                </section>
            </div>

            <div id="tab-bookings" class="profile-tab-pane" style="display:none;">
                <section class="detail-panel mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="mb-0">My Bookings</h2>
                        <a href="{{ route('bookings.index') }}" class="small fw-semibold text-decoration-none">Lihat semua pesanan →</a>
                    </div>
                    @if ($bookings->isEmpty())
                        <p class="text-muted mb-0">You haven't booked a trip yet.</p>
                    @else
                        <div class="vstack gap-3">
                            @foreach ($bookings as $booking)
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 border-bottom pb-3">
                                    <div>
                                        <strong style="color:#17233b;">{{ $booking->schedule->trip->title }}</strong>
                                        <p class="text-muted small mb-0">{{ $booking->booking_code }} · {{ $booking->schedule->date->translatedFormat('d M Y') }} · {{ $booking->quantity }} pax</p>
                                    </div>
                                    <div class="text-end">
                                        <span class="rating d-inline-block mb-1">{{ ucfirst($booking->status) }}</span>
                                        <p class="mb-0 fw-bold">Rp {{ number_format($booking->total, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</div>
</main>
@push('scripts')
<script>
(function () {
    var links = document.querySelectorAll('.profile-nav a[data-tab]');
    var panes = document.querySelectorAll('.profile-tab-pane');
    links.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            links.forEach(function (l) { l.classList.remove('active'); });
            link.classList.add('active');
            panes.forEach(function (p) { p.style.display = (p.id === link.dataset.tab) ? '' : 'none'; });
        });
    });
})();
</script>
@endpush
@endsection
