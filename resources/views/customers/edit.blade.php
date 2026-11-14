@extends('layouts.app')

@section('content')
<div class="form-page-container">
    <header class="form-page-header">
        <a href="{{ route('customers.index') }}" class="back-link">&larr; Back to Customer Accounts</a>
        <h1 class="page-title">Edit Customer Account</h1>
        <p class="page-lead">
            Update contact details for <strong>{{ $customer->full_name }}</strong> while preserving original registration timestamps.
        </p>
    </header>

    @include('partials.form_errors')

    @include('customers._form', [
        'action' => route('customers.update', $customer),
        'method' => 'PUT',
        'mode' => 'edit',
        'customer' => $customer,
    ])
</div>
@endsection
