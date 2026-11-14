<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'POS Eloquent' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ preg_replace('~^https?:~', '', asset('favicon.svg')) }}">
    <link rel="alternate icon" href="{{ preg_replace('~^https?:~', '', asset('favicon.ico')) }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ preg_replace('~^https?:~', '', asset('assets/css/style.css')) }}">
    <script src="{{ preg_replace('~^https?:~', '', asset('assets/js/app.js')) }}" defer></script>
</head>
<body class="page-{{ $activePage ?? 'home' }}">
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="site-header" role="banner">
        <div class="container header-container">
            <div class="brand-group">
                <a href="{{ route('home') }}" class="brand-link" aria-label="POS Eloquent Home">
                    <span class="brand-title">POS Eloquent</span>
                </a>
            </div>

            <nav class="site-nav" aria-label="Main">
                <ul class="nav-list" role="list">
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ ($activePage ?? '') === 'home' ? 'active' : '' }}" {!! ($activePage ?? '') === 'home' ? 'aria-current="page"' : '' !!}>Home</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ ($activePage ?? '') === 'dashboard' || request()->routeIs('dashboard') ? 'active' : '' }}" {!! ($activePage ?? '') === 'dashboard' || request()->routeIs('dashboard') ? 'aria-current="page"' : '' !!}>Dashboard</a>
                        </li>
                    @endauth
                    <li class="nav-item">
                        <a href="{{ route('customers.index') }}" class="nav-link {{ ($activePage ?? '') === 'customers' ? 'active' : '' }}" {!! ($activePage ?? '') === 'customers' ? 'aria-current="page"' : '' !!}>Customers</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ ($activePage ?? '') === 'users' ? 'active' : '' }}" {!! ($activePage ?? '') === 'users' ? 'aria-current="page"' : '' !!}>Users</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('about') }}" class="nav-link {{ ($activePage ?? '') === 'about' ? 'active' : '' }}" {!! ($activePage ?? '') === 'about' ? 'aria-current="page"' : '' !!}>About</a>
                    </li>
                </ul>
            </nav>

            <div class="header-auth">
                @auth
                    <a href="{{ route('profile.edit') }}"
                       class="operator-chip {{ request()->routeIs('profile.edit') ? 'active' : '' }}"
                       aria-label="Operator profile for {{ Auth::user()->full_name }}">
                        <img
                            src="{{ Auth::user()->avatarUrl() }}"
                            alt=""
                            width="26"
                            height="26"
                            class="operator-chip-avatar"
                        >
                        <span class="operator-chip-name">{{ Auth::user()->full_name }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="button button-secondary button-compact">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Sign In</a>
                    <a href="{{ route('register') }}" class="button button-primary button-compact">Register</a>
                @endauth
            </div>
        </div>
    </header>

    <main id="main-content" class="main-content" role="main" tabindex="-1">
        <div class="container content-container">
            @include('partials.flash')

            @isset($header)
                <div class="directory-header directory-header-split">
                    <div class="directory-header-copy">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            @if (isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </div>
    </main>

    <dialog id="confirm-delete-dialog" class="confirm-dialog" closedby="any" aria-labelledby="confirm-delete-title">
        <div class="confirm-dialog-body">
            <h2 id="confirm-delete-title" class="confirm-dialog-title">Confirm Record Deletion</h2>
            <p id="confirm-delete-message" class="confirm-dialog-text">
                Are you sure you want to remove this record? This action cannot be undone.
            </p>
            <div class="confirm-dialog-actions">
                <button type="button" id="confirm-delete-cancel" class="button button-secondary" autofocus>Cancel</button>
                <button type="button" id="confirm-delete-confirm" class="button button-danger">Confirm Delete</button>
            </div>
        </div>
    </dialog>

    <footer class="site-footer" role="contentinfo">
        <div class="container footer-container">
            <p class="footer-text">IT0049 Web System Technologies &bull; Technical Formative Assessment 8</p>
            <p class="footer-subtext">Laravel POS &bull; Resource Controllers, Auth Middleware &amp; Breeze</p>
        </div>
    </footer>
</body>
</html>
