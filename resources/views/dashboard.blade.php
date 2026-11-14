@extends('layouts.app')

@section('content')
<div class="home-layout">
    <section class="overview-section">
        <header class="directory-header directory-header-split">
            <div class="directory-header-copy">
                <h1 class="page-title">Operations Dashboard</h1>
                <p class="page-lead">
                    Signed in as <strong>{{ Auth::user()->full_name }}</strong>
                    (<span class="cell-mono">{{ Auth::user()->username }}</span> &bull; {{ Auth::user()->role }}).
                </p>
            </div>
            <div class="directory-actions">
                <a href="{{ route('profile.edit') }}" class="button button-secondary">Account Profile</a>
            </div>
        </header>

        <div class="actions-grid">
            <article class="action-card">
                <div class="stat-kicker">CUSTOMER LEDGER</div>
                <h2 class="card-heading tabular-num">{{ $customerCount }} {{ \Illuminate\Support\Str::plural('Customer', $customerCount) }}</h2>
                <p class="card-text">
                    Full CRUD access via <code>CustomerController</code> resource routes (<code>index, show, create, store, edit, update, destroy</code>).
                </p>
                <div class="card-action card-actions-split">
                    <a href="{{ route('customers.index') }}" class="button button-primary">Manage Customers</a>
                    <a href="{{ route('customers.create') }}" class="button button-secondary">+ New Customer</a>
                </div>
            </article>

            <article class="action-card">
                <div class="stat-kicker">STAFF DIRECTORY</div>
                <h2 class="card-heading tabular-num">{{ $userCount }} {{ \Illuminate\Support\Str::plural('Operator', $userCount) }}</h2>
                <p class="card-text">
                    Manage staff roles, authentication credentials, and 256&times;256 prepared avatars via <code>UserController</code> resource routes.
                </p>
                <div class="card-action card-actions-split">
                    <a href="{{ route('users.index') }}" class="button button-primary">Manage Users</a>
                    <a href="{{ route('users.create') }}" class="button button-secondary">+ New User</a>
                </div>
            </article>
        </div>
    </section>

    <section class="overview-section" aria-labelledby="session-heading">
        <h2 id="session-heading" class="section-title">Active Session Status</h2>
        <div class="details-card">
            <ul class="details-list">
                <li>
                    <strong>Authenticated Email:</strong>
                    <span class="cell-mono">{{ Auth::user()->email }}</span>
                </li>
                <li>
                    <strong>Assigned Role:</strong>
                    <span>{{ Auth::user()->role }}</span>
                </li>
                <li>
                    <strong>Middleware Protection:</strong>
                    <span>Verified active <code>auth</code> web session guard</span>
                </li>
                <li>
                    <strong>Profile Management:</strong>
                    <span>Update email, password, or delete personal account in <a href="{{ route('profile.edit') }}" class="table-link">Profile Settings</a></span>
                </li>
            </ul>
        </div>
    </section>
</div>
@endsection
