<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sign In | POS Eloquent' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ preg_replace('~^https?:~', '', asset('favicon.svg')) }}">
    <link rel="alternate icon" href="{{ preg_replace('~^https?:~', '', asset('favicon.ico')) }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ preg_replace('~^https?:~', '', asset('assets/css/style.css')) }}">
    <script src="{{ preg_replace('~^https?:~', '', asset('assets/js/app.js')) }}" defer></script>
</head>
<body class="page-auth">
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
                        <a href="{{ route('home') }}" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customers.index') }}" class="nav-link">Customers</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link">Users</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('about') }}" class="nav-link">About</a>
                    </li>
                </ul>
            </nav>

            <div class="header-auth">
                <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" {!! request()->routeIs('login') ? 'aria-current="page"' : '' !!}>Sign In</a>
                <a href="{{ route('register') }}" class="button button-primary button-compact">Register</a>
            </div>
        </div>
    </header>

    <main id="main-content" class="main-content" role="main" tabindex="-1">
        <div class="container content-container auth-shell">
            {{ $slot }}
        </div>
    </main>

    <footer class="site-footer" role="contentinfo">
        <div class="container footer-container">
            <p class="footer-text">IT0049 Web System Technologies &bull; Technical Formative Assessment 8</p>
            <p class="footer-subtext">Laravel POS &bull; Resource Controllers, Auth Middleware &amp; Breeze</p>
        </div>
    </footer>
</body>
</html>
