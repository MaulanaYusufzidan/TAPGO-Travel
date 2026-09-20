<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — TAPGO TRAVEL')</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <aside class="bg-dark text-light p-3" style="width: 240px; flex-shrink: 0;">
            <p class="fw-bold fs-5 text-white mb-4">TAPGO TRAVEL <span class="d-block small text-secondary">Admin Panel</span></p>
            <ul class="nav nav-pills flex-column gap-1">
                <li class="nav-item"><a class="nav-link text-light {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-light {{ request()->routeIs('admin.destinations.*') ? 'active' : '' }}" href="{{ route('admin.destinations.index') }}">Destinations</a></li>
                <li class="nav-item"><a class="nav-link text-light {{ request()->routeIs('admin.trips.*') ? 'active' : '' }}" href="{{ route('admin.trips.index') }}">Trips</a></li>
                <li class="nav-item"><a class="nav-link text-light {{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}" href="{{ route('admin.schedules.index') }}">Schedules</a></li>
                <li class="nav-item"><a class="nav-link text-light {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                <li class="nav-item"><a class="nav-link text-light {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">Payments</a></li>
                <li class="nav-item"><a class="nav-link text-light {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">Customers</a></li>
            </ul>
            <hr class="border-secondary">
            <a href="{{ url('/') }}" class="small text-secondary text-decoration-none">&larr; Kembali ke situs</a>
        </aside>

        <main class="flex-grow-1 p-4 bg-light">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
