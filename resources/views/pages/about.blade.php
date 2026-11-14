@extends('layouts.app')

@section('content')
<div class="about-container">
    <header class="about-header">
        <h1 class="page-title">About This Application</h1>
        <p class="page-lead">
            Technical Formative Assessment 8 refactors CRUD endpoints into Laravel RESTful resource controllers and locks customer and user management behind Laravel Breeze authentication and <code>auth</code> middleware.
        </p>
    </header>

    <section class="content-section" aria-labelledby="flow-heading">
        <h2 id="flow-heading" class="section-title">Authenticated Request Execution Flow</h2>
        <p class="section-lead">
            Every protected HTTP request passes through session verification, resource routing, validated controller execution, and Blade rendering:
        </p>

        <ol class="flow-list">
            <li class="flow-item">
                <div class="flow-marker">1</div>
                <div class="flow-content">
                    <h3 class="flow-step-title">Middleware Access Control</h3>
                    <p class="flow-step-text">Laravel&rsquo;s <code>auth</code> middleware intercepts unauthenticated requests to <code>/dashboard</code>, <code>/customers/*</code>, <code>/users/*</code>, and <code>/profile</code> and redirects guests to <code>/login</code> while preserving intended destination URLs.</p>
                </div>
            </li>

            <li class="flow-item">
                <div class="flow-marker">2</div>
                <div class="flow-content">
                    <h3 class="flow-step-title">Breeze Session &amp; Password Handling</h3>
                    <p class="flow-step-text">Laravel Breeze scaffolds registration, sign-in, password reset, logout, and profile management backed by CSRF verification, rate limiting, session regeneration, and bcrypt hashing.</p>
                </div>
            </li>

            <li class="flow-item">
                <div class="flow-marker">3</div>
                <div class="flow-content">
                    <h3 class="flow-step-title">RESTful Resource Controllers</h3>
                    <p class="flow-step-text"><code>Route::resource('customers', ...)</code> and <code>Route::resource('users', ...)</code> bind all seven standard actions (<code>index</code>, <code>create</code>, <code>store</code>, <code>show</code>, <code>edit</code>, <code>update</code>, <code>destroy</code>) cleanly with route-model binding.</p>
                </div>
            </li>

            <li class="flow-item">
                <div class="flow-marker">4</div>
                <div class="flow-content">
                    <h3 class="flow-step-title">Form Requests &amp; Avatar Storage</h3>
                    <p class="flow-step-text"><code>SaveCustomerRequest</code> and <code>SaveUserRequest</code> validate incoming payloads while <code>AvatarService</code> prepares 256&times;256 thumbnails on <code>Storage::disk('public')</code>.</p>
                </div>
            </li>
        </ol>
    </section>

    <section class="content-section" aria-labelledby="comparison-heading">
        <h2 id="comparison-heading" class="section-title">Routing &amp; Protection Pattern</h2>
        <p class="section-lead">
            Unified <code>Route::resource()</code> registration wrapped inside Laravel&rsquo;s <code>auth</code> middleware group:
        </p>

        <div class="comparison-grid">
            <div class="code-column">
                <h3 class="code-title">Protected Resource Routes (routes/web.php)</h3>
                <pre class="code-block" role="region" aria-label="Protected resource routes example" tabindex="0"><code>Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])
        ->name('dashboard');

    Route::resource('customers', CustomerController::class);
    Route::resource('users', UserController::class);
});</code></pre>
            </div>

            <div class="code-column">
                <h3 class="code-title">Seven Conventional Controller Methods</h3>
                <pre class="code-block" role="region" aria-label="Resource controller methods" tabindex="0"><code>GET    /customers              -&gt; index()
GET    /customers/create       -&gt; create()
POST   /customers              -&gt; store()
GET    /customers/{customer}   -&gt; show()
GET    /customers/{customer}/edit -&gt; edit()
PUT    /customers/{customer}   -&gt; update()
DELETE /customers/{customer}   -&gt; destroy()</code></pre>
            </div>
        </div>
    </section>
</div>
@endsection
