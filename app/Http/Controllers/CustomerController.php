<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display the customer accounts directory populated via Eloquent ORM.
     */
    public function index(): View
    {
        $customers = Customer::query()->orderBy('id')->get();

        return view('customers.index', [
            'title' => 'Customer Accounts | POS Eloquent',
            'activePage' => 'customers',
            'customers' => $customers,
        ]);
    }

    /**
     * Display the form for creating a new customer account.
     */
    public function create(): View
    {
        return view('customers.create', [
            'title' => 'New Customer Account | POS Eloquent',
            'activePage' => 'customers',
            'customer' => new Customer,
        ]);
    }

    /**
     * Validate and persist a new customer record in the database.
     */
    public function store(SaveCustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer account created successfully.');
    }

    /**
     * Display an individual customer account record.
     */
    public function show(Customer $customer): View
    {
        return view('customers.show', [
            'title' => $customer->full_name.' | Customer Account | POS Eloquent',
            'activePage' => 'customers',
            'customer' => $customer,
        ]);
    }

    /**
     * Display the form pre-filled with an existing customer's profile data.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', [
            'title' => 'Edit Customer Account | POS Eloquent',
            'activePage' => 'customers',
            'customer' => $customer,
        ]);
    }

    /**
     * Validate and update an existing customer record in the database.
     */
    public function update(SaveCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer account updated successfully.');
    }

    /**
     * Remove an existing customer record from storage.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer account deleted successfully.');
    }
}
