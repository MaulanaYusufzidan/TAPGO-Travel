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
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar d-none d-lg-flex">
            @include('admin.partials.sidebar-nav')
        </aside>

        <div class="offcanvas offcanvas-start admin-sidebar admin-sidebar--offcanvas d-lg-none" tabindex="-1" id="adminSidebarOffcanvas" aria-labelledby="adminSidebarOffcanvasLabel">
            <div class="offcanvas-header">
                <span id="adminSidebarOffcanvasLabel" class="visually-hidden">Admin navigation</span>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body pt-0">
                @include('admin.partials.sidebar-nav')
            </div>
        </div>

        <div class="admin-main">
            <header class="admin-topbar">
                <button type="button" class="admin-topbar__burger d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#adminSidebarOffcanvas" aria-label="Open navigation" aria-controls="adminSidebarOffcanvas">
                    <span></span><span></span><span></span>
                </button>
                <div>
                    <h1>TAPGO Admin</h1>
                    @hasSection('page-subtitle')<p>@yield('page-subtitle')</p>@endif
                </div>
                <div class="admin-topbar__account">
                    <span class="admin-topbar__name">{{ auth()->user()->name ?? 'Admin' }}</span>
                </div>
            </header>

            <main class="admin-content">
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
