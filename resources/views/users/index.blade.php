@extends('layouts.app')

@section('content')
<div class="directory-container">
    <header class="directory-header directory-header-split">
        <div class="directory-header-copy">
            <h1 class="page-title">User Accounts</h1>
            <p class="page-lead">
                Operational personnel, cashiers, and administrators retrieved through the <code>User</code> Eloquent ORM model. Total Users: {{ count($users) }}.
            </p>
        </div>
        <div class="directory-actions">
            <a href="{{ route('users.create') }}" class="button button-primary">+ New User</a>
        </div>
    </header>

    <div class="table-container" tabindex="0" role="region" aria-labelledby="users-table-caption">
        <table class="data-table">
            <caption id="users-table-caption" class="visually-hidden">Staff user accounts directory</caption>
            <thead>
                <tr>
                    <th scope="col" class="th-num">#</th>
                    <th scope="col" class="th-avatar">Avatar</th>
                    <th scope="col">Username</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">System Role</th>
                    <th scope="col" class="th-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    @php
                        $role = $user->role ?? 'Staff Member';
                        $roleKey = strtolower(explode(' ', $role)[0]);
                        $hasAvatar = $user->hasAvatar();
                    @endphp
                    <tr>
                        <td class="td-num" data-label="#">{{ sprintf('%02d', $index + 1) }}</td>
                        <td class="cell-avatar" data-label="Avatar">
                            <img
                                src="{{ $user->avatarUrl() }}"
                                alt="{{ $hasAvatar ? $user->full_name . ' profile avatar' : 'Default avatar placeholder for ' . $user->full_name }}"
                                class="avatar-thumb {{ $hasAvatar ? 'avatar-uploaded' : 'avatar-placeholder' }}"
                                width="40"
                                height="40"
                                loading="lazy"
                            >
                        </td>
                        <td class="cell-mono tabular-num" data-label="Username">{{ $user->username }}</td>
                        <th scope="row" class="cell-primary" data-label="Full Name">{{ $user->full_name }}</th>
                        <td class="cell-role" data-label="System Role">
                            <span class="role-badge role-{{ $roleKey }}">{{ $role }}</span>
                        </td>
                        <td class="cell-actions" data-label="Actions">
                            <a href="{{ route('users.edit', $user) }}"
                               class="table-action-link"
                               aria-label="Edit {{ $user->full_name }}">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="cell-empty">No user accounts found yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
