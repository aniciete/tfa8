@extends('layouts.app')

@section('content')
<div class="form-page-container">
    <header class="form-page-header">
        <a href="{{ route('users.index') }}" class="back-link">&larr; Back to User Accounts</a>
        <h1 class="page-title">New User Account</h1>
        <p class="page-lead">
            Add an operator or cashier account with unique username validation and optional 256&times;256 avatar upload.
        </p>
    </header>

    @include('partials.form_errors')

    @include('users._form', [
        'action' => route('users.store'),
        'method' => 'POST',
        'mode' => 'create',
        'user' => $user,
        'roles' => $roles,
    ])
</div>
@endsection
