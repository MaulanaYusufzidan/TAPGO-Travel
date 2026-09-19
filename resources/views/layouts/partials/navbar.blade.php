<nav class="navbar navbar-expand-xl tapgo-navbar sticky-top" aria-label="Main navigation">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}" aria-label="TAPGO Travel home"><span class="brand-mark">T</span><span>TAPGO <em>Travel</em></span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Open navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-xl-auto tapgo-nav-links">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('hotels.*') ? 'active' : '' }}" href="{{ route('hotels.index') }}">Hotels</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('flights.*') ? 'active' : '' }}" href="{{ route('flights.index') }}">Flights</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('destinations.*') ? 'active' : '' }}" href="{{ route('destinations.index') }}">Destinations</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('blog') ? 'active' : '' }}" href="{{ route('blog') }}">Blog</a></li>
                <li class="nav-item dropdown"><a class="nav-link dropdown-toggle {{ request()->routeIs('about', 'career', 'contact') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">More</a><ul class="dropdown-menu"><li><a class="dropdown-item" href="{{ route('about') }}">About TAPGO</a></li><li><a class="dropdown-item" href="{{ route('career') }}">Careers</a></li><li><a class="dropdown-item" href="{{ route('contact') }}">Contact</a></li></ul></li>
            </ul>
            <div class="tapgo-nav-actions pt-3 pt-xl-0">
                <div class="dropdown"><button class="nav-utility dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">IDR <span aria-hidden="true">🌐</span></button><ul class="dropdown-menu dropdown-menu-end"><li><h6 class="dropdown-header">Language</h6></li><li><button class="dropdown-item active" type="button">English</button></li><li><button class="dropdown-item" type="button">Bahasa Indonesia</button></li><li><hr class="dropdown-divider"></li><li><h6 class="dropdown-header">Currency</h6></li><li><button class="dropdown-item active" type="button">IDR — Rupiah</button></li><li><button class="dropdown-item" type="button">USD — US Dollar</button></li></ul></div>
                @guest
                    <a class="nav-login" href="{{ route('login') }}">Login</a><a class="btn btn-primary tapgo-signup" href="{{ route('register') }}">Sign up</a>
                @else
                    <div class="dropdown"><button class="account-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><span class="account-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span><span class="account-name">{{ Auth::user()->name }}</span></button><ul class="dropdown-menu dropdown-menu-end account-menu"><li class="account-menu__head"><strong>{{ Auth::user()->name }}</strong><span>{{ Auth::user()->email }}</span></li><li><hr class="dropdown-divider"></li><li><a class="dropdown-item" href="{{ route('profile') }}">My Profile</a></li><li><a class="dropdown-item" href="{{ route('profile', ['tab' => 'bookings']) }}">My Bookings</a></li><li><a class="dropdown-item" href="{{ route('profile', ['tab' => 'saved']) }}">Saved Places</a></li><li><a class="dropdown-item" href="{{ route('profile', ['tab' => 'settings']) }}">Settings</a></li><li><hr class="dropdown-divider"></li><li><form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="dropdown-item text-danger">Logout</button></form></li></ul></div>
                @endguest
            </div>
        </div>
    </div>
</nav>
