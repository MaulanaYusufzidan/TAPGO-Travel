{{--
    TAPGO TRAVEL — Navigation Component
    Ref: PRD section 7 (Information Architecture) & section 9 (Homepage Requirements)
--}}
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
            TAPGO TRAVEL
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar" aria-controls="mainNavbar"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-lg-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active fw-semibold' : '' }}" href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('destinations*') ? 'active fw-semibold' : '' }}" href="{{ url('/destinations') }}">Destinations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('trips*') ? 'active fw-semibold' : '' }}" href="{{ url('/trips') }}">Trips</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('experiences*') ? 'active fw-semibold' : '' }}" href="{{ url('/experiences') }}">Experiences</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('travel-guide*') ? 'active fw-semibold' : '' }}" href="{{ url('/travel-guide') }}">Travel Guide</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about*') ? 'active fw-semibold' : '' }}" href="{{ url('/about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contact*') ? 'active fw-semibold' : '' }}" href="{{ url('/contact') }}">Contact</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-lg-3 align-items-lg-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary rounded-pill px-3 ms-lg-2" href="{{ url('/register') }}">Register</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="accountDropdown"
                           role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ Auth::user()->name ?? 'Account' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                            <li><a class="dropdown-item" href="{{ url('/account') }}">Dashboard</a></li>
                            <li><a class="dropdown-item" href="{{ url('/account/bookings') }}">My Bookings</a></li>
                            <li><a class="dropdown-item" href="{{ url('/account/wishlist') }}">Wishlist</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
