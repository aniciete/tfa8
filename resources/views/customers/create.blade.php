@extends('layouts.app')

@section('content')
<div class="form-page-container">
    <header class="form-page-header">
        <a href="{{ route('customers.index') }}" class="back-link">&larr; Back to Customer Accounts</a>
        <h1 class="page-title">New Customer Account</h1>
        <p class="page-lead">
            Register a retail or commercial customer profile backed by Eloquent ORM persistence.
        </p>
    </header>

    @include('partials.form_errors')

    @include('customers._form', [
        'action' => route('customers.store'),
        'method' => 'POST',
        'mode' => 'create',
        'customer' => $customer,
    ])
</div>
@endsection
