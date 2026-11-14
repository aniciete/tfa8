@extends('layouts.app')

@section('content')
<div class="form-page-container">
    <nav class="breadcrumb-nav" aria-label="Breadcrumb">
        <a href="{{ route('customers.index') }}" class="breadcrumb-link">&larr; Back to Customer Accounts</a>
    </nav>

    <header class="directory-header directory-header-split">
        <div class="directory-header-copy">
            <h1 class="page-title">{{ $customer->full_name }}</h1>
            <p class="page-lead">
                Customer account profile rendered via <code>CustomerController::show()</code>.
            </p>
        </div>
        <div class="directory-actions directory-actions-row">
            <a href="{{ route('customers.edit', $customer) }}" class="button button-primary">Edit Customer</a>
            <form action="{{ route('customers.destroy', $customer) }}"
                  method="POST"
                  class="inline-delete-form"
                  data-confirm-delete
                  data-record-name="{{ $customer->full_name }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="button button-danger">Delete Customer</button>
            </form>
        </div>
    </header>

    <section class="form-card" aria-labelledby="customer-detail-heading">
        <header class="form-card-header">
            <h2 id="customer-detail-heading" class="form-card-title">Customer Record Summary</h2>
            <span class="form-card-docket tabular-num">ID #{{ sprintf('%02d', $customer->id) }}</span>
        </header>

        <dl class="detail-grid">
            <div class="detail-row">
                <dt class="detail-label">Full Name</dt>
                <dd class="detail-value">{{ $customer->full_name }}</dd>
            </div>
            <div class="detail-row">
                <dt class="detail-label">Email Address</dt>
                <dd class="detail-value">
                    <a href="mailto:{{ $customer->email }}" class="table-link">{{ $customer->email }}</a>
                </dd>
            </div>
            <div class="detail-row">
                <dt class="detail-label">Phone Number</dt>
                <dd class="detail-value cell-mono tabular-num">{{ $customer->phone ?: 'Not provided' }}</dd>
            </div>
            @if ($customer->created_at)
                <div class="detail-row">
                    <dt class="detail-label">Registered On</dt>
                    <dd class="detail-value cell-mono tabular-num">{{ $customer->created_at->format('Y-m-d H:i') }}</dd>
                </div>
            @endif
            @if ($customer->updated_at)
                <div class="detail-row">
                    <dt class="detail-label">Last Updated</dt>
                    <dd class="detail-value cell-mono tabular-num">{{ $customer->updated_at->format('Y-m-d H:i') }}</dd>
                </div>
            @endif
        </dl>
    </section>
</div>
@endsection
