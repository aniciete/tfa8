<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the POS application landing page.
     */
    public function home(): View
    {
        return view('pages.home', [
            'title' => 'Home | POS Eloquent',
            'activePage' => 'home',
        ]);
    }

    /**
     * Display the authenticated POS operations dashboard.
     */
    public function dashboard(): View
    {
        return view('dashboard', [
            'title' => 'Dashboard | POS Eloquent',
            'activePage' => 'dashboard',
            'customerCount' => Customer::query()->count(),
            'userCount' => User::query()->count(),
        ]);
    }

    /**
     * Display the MVC architecture and request flow documentation page.
     */
    public function about(): View
    {
        return view('pages.about', [
            'title' => 'About | POS Eloquent',
            'activePage' => 'about',
        ]);
    }
}
