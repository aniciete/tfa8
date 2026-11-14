@extends('layouts.app')

@section('content')
<div class="home-layout">
    <section class="overview-section">
        <h1 class="page-title">Point-of-Sale Eloquent</h1>
        <p class="page-lead">
            A modular Laravel POS web application demonstrating full RESTful <code>Route::resource()</code> controllers, Laravel Breeze session authentication, and protected customer and staff account management.
        </p>

        @guest
            <div class="alert alert-info" role="status">
                <strong>Protected Directories:</strong> Customer Accounts and User Accounts require an authenticated session. Visiting any protected route while signed out redirects automatically to Sign In.
            </div>
        @endguest

        <div class="actions-grid">
            <article class="action-card">
                <h2 class="card-heading">Customer Accounts</h2>
                <p class="card-text">
                    Browse, inspect, create, update, and remove retail client profiles through <code>CustomerController</code> resource actions protected by <code>auth</code> middleware.
                </p>
                <div class="card-action card-actions-split">
                    <a href="{{ route('customers.index') }}" class="button button-primary">
                        @auth View Customers @else Customers (Auth Required) @endauth
                    </a>
                    @auth
                        <a href="{{ route('customers.create') }}" class="button button-secondary">+ New Customer</a>
                    @else
                        <a href="{{ route('login') }}" class="button button-secondary">Sign In to Manage</a>
                    @endauth
                </div>
            </article>

            <article class="action-card">
                <h2 class="card-heading">User Accounts</h2>
                <p class="card-text">
                    Manage store staff credentials, operational roles, login emails, and 256&times;256 prepared avatars through <code>UserController</code> resource actions.
                </p>
                <div class="card-action card-actions-split">
                    <a href="{{ route('users.index') }}" class="button button-primary">
                        @auth View Users @else Users (Auth Required) @endauth
                    </a>
                    @auth
                        <a href="{{ route('users.create') }}" class="button button-secondary">+ New User</a>
                    @else
                        <a href="{{ route('register') }}" class="button button-secondary">Register Operator</a>
                    @endauth
                </div>
            </article>
        </div>
    </section>

    <section class="overview-section" aria-labelledby="details-heading">
        <h2 id="details-heading" class="section-title">Application Architecture Overview</h2>
        <div class="details-card">
            <ul class="details-list">
                <li>
                    <strong>Framework:</strong>
                    <span>Laravel 13 with PHP 8.3+</span>
                </li>
                <li>
                    <strong>Resource Routing:</strong>
                    <span>Single <code>Route::resource()</code> registrations mapped to <code>index, create, store, show, edit, update, destroy</code></span>
                </li>
                <li>
                    <strong>Authentication:</strong>
                    <span>Laravel Breeze Blade scaffold with bcrypt hashing, CSRF protection, and session management</span>
                </li>
                <li>
                    <strong>Access Control:</strong>
                    <span><code>auth</code> middleware group guarding <code>/dashboard</code>, <code>/customers/*</code>, <code>/users/*</code>, <code>/profile</code>, and avatar media</span>
                </li>
            </ul>
        </div>
    </section>
</div>
@endsection
