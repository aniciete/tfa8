@extends('layouts.app')

@section('content')
<div class="directory-container">
    <header class="directory-header directory-header-split">
        <div class="directory-header-copy">
            <h1 class="page-title">Customer Accounts</h1>
            <p class="page-lead">
                Registered retail and commercial client profiles retrieved through the <code>Customer</code> Eloquent ORM model. Total Records: {{ count($customers) }}.
            </p>
        </div>
        <div class="directory-actions">
            <a href="{{ route('customers.create') }}" class="button button-primary">+ New Customer</a>
        </div>
    </header>

    <div class="table-container" tabindex="0" role="region" aria-labelledby="customers-table-caption">
        <table class="data-table">
            <caption id="customers-table-caption" class="visually-hidden">Customer accounts directory</caption>
            <thead>
                <tr>
                    <th scope="col" class="th-num">#</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Email Address</th>
                    <th scope="col">Phone Number</th>
                    <th scope="col" class="th-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $index => $customer)
                    <tr>
                        <td class="td-num" data-label="#">{{ sprintf('%02d', $index + 1) }}</td>
                        <th scope="row" class="cell-primary" data-label="Full Name">{{ $customer->full_name }}</th>
                        <td class="cell-email" data-label="Email Address">
                            <a href="mailto:{{ $customer->email }}" class="table-link">{{ $customer->email }}</a>
                        </td>
                        <td class="cell-mono tabular-num" data-label="Phone Number">{{ $customer->phone ?: '—' }}</td>
                        <td class="cell-actions" data-label="Actions">
                            <a href="{{ route('customers.edit', $customer) }}"
                               class="table-action-link"
                               aria-label="Edit {{ $customer->full_name }}">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="cell-empty">No customer accounts found yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
