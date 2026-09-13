{{--
    TAPGO TRAVEL — Base Layout
    Master layout dipakai oleh seluruh halaman publik & account.
    Komponen navbar & footer akan diisi pada commit berikutnya
    (feat: create navigation component, feat: create footer component).
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO (PRD section 36) --}}
    <title>@yield('title', config('app.name', 'TAPGO TRAVEL'))</title>
    <meta name="description" content="@yield('meta_description', 'Discover More. Travel Better. Temukan destinasi wisata dan paket perjalanan terbaik di Indonesia bersama TAPGO TRAVEL.')">
    <link rel="canonical" href="@yield('canonical', url()->current())">

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', config('app.name', 'TAPGO TRAVEL'))">
    <meta property="og:description" content="@yield('og_description', 'Discover More. Travel Better.')">
    @stack('meta')

    <link rel="icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">

    {{-- Skip link — accessibility (PRD section 37) --}}
    <a class="visually-hidden-focusable" href="#site-content">Skip to content</a>

    <header id="site-header">
        @include('layouts.partials.navbar')
    </header>

    <main id="site-content" class="flex-grow-1">
        @if (session('status'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <footer id="site-footer">
        @include('layouts.partials.footer')
    </footer>

    @stack('scripts')
</body>
</html>
