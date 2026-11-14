@extends('layouts.app')

@section('content')
@php
    $hasAvatar = $user->hasAvatar();
    $role = $user->role ?? 'Staff Member';
    $roleKey = strtolower(explode(' ', $role)[0]);
    $isCurrentUser = auth()->check() && $user->is(auth()->user());
@endphp

<div class="form-page-container">
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
        <a href="{{ route('users.index') }}" class="breadcrumb-link">&larr; Back to User Accounts</a>
    </nav>

    <header class="directory-header directory-header-split">
        <div class="directory-header-copy">
            <h1 class="page-title">{{ $user->full_name }}</h1>
            <p class="page-lead">
                Staff account profile rendered via <code>UserController::show()</code>.
            </p>
        </div>
        <div class="directory-actions directory-actions-row">
            <a href="{{ route('users.edit', $user) }}" class="button button-primary">Edit User</a>
            @if (! $isCurrentUser)
                <form action="{{ route('users.destroy', $user) }}"
                      method="POST"
                      class="inline-delete-form"
                      data-confirm-delete
                      data-record-name="{{ $user->full_name }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button button-danger">Delete User</button>
                </form>
            @endif
        </div>
    </header>

    <section class="form-card" aria-labelledby="user-detail-heading">
        <header class="form-card-header">
            <h2 id="user-detail-heading" class="form-card-title">Staff Operator Profile</h2>
            <span class="form-card-docket tabular-num">ID #{{ sprintf('%02d', $user->id) }}</span>
        </header>

        <div class="detail-profile-banner">
            <img
                src="{{ $user->avatarUrl() }}"
                alt="{{ $hasAvatar ? $user->full_name . ' profile avatar' : 'Default avatar placeholder for ' . $user->full_name }}"
                class="avatar-preview-img"
                width="96"
                height="96"
            >
            <div class="detail-profile-summary">
                <p class="detail-profile-name">{{ $user->full_name }}</p>
                <p class="detail-profile-handle cell-mono">@<span>{{ $user->username }}</span></p>
                <span class="role-badge role-{{ $roleKey }}">{{ $role }}</span>
            </div>
        </div>

        <dl class="detail-grid">
            <div class="detail-row">
                <dt class="detail-label">Username</dt>
                <dd class="detail-value cell-mono">{{ $user->username }}</dd>
            </div>
            <div class="detail-row">
                <dt class="detail-label">Login Email</dt>
                <dd class="detail-value cell-mono">{{ $user->email ?: 'Not configured' }}</dd>
            </div>
            <div class="detail-row">
                <dt class="detail-label">System Role</dt>
                <dd class="detail-value">{{ $role }}</dd>
            </div>
            <div class="detail-row">
                <dt class="detail-label">Avatar Storage Path</dt>
                <dd class="detail-value cell-mono">{{ $hasAvatar ? $user->avatar : 'Using default SVG placeholder' }}</dd>
            </div>
            @if ($user->created_at)
                <div class="detail-row">
                    <dt class="detail-label">Created On</dt>
                    <dd class="detail-value cell-mono tabular-num">{{ $user->created_at->format('Y-m-d H:i') }}</dd>
                </div>
            @endif
        </dl>
    </section>
</div>
@endsection
