@extends('layouts.app')

@section('content')
<div class="form-page-container">
    <header class="form-page-header">
        <a href="{{ route('users.index') }}" class="back-link">&larr; Back to User Accounts</a>
        <h1 class="page-title">Edit User Account</h1>
        <p class="page-lead">
            Update operator details, assigned role, and profile avatar stored on the public disk for <strong>{{ $user->full_name }}</strong>.
        </p>
    </header>

    @include('partials.form_errors')

    @include('users._form', [
        'action' => route('users.update', $user),
        'method' => 'PUT',
        'mode' => 'edit',
        'user' => $user,
        'roles' => $roles,
    ])
</div>
@endsection
