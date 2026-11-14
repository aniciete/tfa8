@extends('layouts.app')

@section('content')
<div class="home-layout">
    <section class="overview-section">
        <h1 class="page-title">Point-of-Sale Eloquent</h1>
        <p class="page-lead">
            A modular Laravel web application demonstrating validated record creation, edit workflows with <code>old()</code> input retention, Eloquent ORM persistence, and processed avatar uploads on the public disk.
        </p>

        <div class="actions-grid">
            <article class="action-card">
                <h2 class="card-heading">Customer Accounts</h2>
                <p class="card-text">
                    Browse, create, and update retail client profiles retrieved from the database using the <code>Customer</code> Eloquent model.
                </p>
                <div class="card-action card-actions-split">
                    <a href="{{ route('customers.index') }}" class="button button-primary">View Customers</a>
                    <a href="{{ route('customers.create') }}" class="button button-secondary">+ New Customer</a>
                </div>
            </article>

            <article class="action-card">
                <h2 class="card-heading">User Accounts</h2>
                <p class="card-text">
                    Manage store staff credentials, operational roles, and 256&times;256 prepared profile avatars backed by <code>Storage::disk('public')</code>.
                </p>
                <div class="card-action card-actions-split">
                    <a href="{{ route('users.index') }}" class="button button-primary">View Users</a>
                    <a href="{{ route('users.create') }}" class="button button-secondary">+ New User</a>
                </div>
            </article>
        </div>
    </section>

    <section class="overview-section" aria-labelledby="details-heading">
        <h2 id="details-heading" class="section-title">Application Overview</h2>
        <div class="details-card">
            <ul class="details-list">
                <li>
                    <strong>Framework:</strong>
                    <span>Laravel 13 running on PHP 8.3+</span>
                </li>
                <li>
                    <strong>Forms &amp; Validation:</strong>
                    <span>Form Requests with <code>old()</code> input retention and <code>$errors</code> feedback</span>
                </li>
                <li>
                    <strong>Database &amp; ORM:</strong>
                    <span>Eloquent models (<code>Customer</code> &amp; <code>User</code>) with schema migrations</span>
                </li>
                <li>
                    <strong>Avatar Storage:</strong>
                    <span>256&times;256 thumbnails prepared with Intervention Image on <code>Storage::disk('public')</code></span>
                </li>
            </ul>
        </div>
    </section>
</div>
@endsection
