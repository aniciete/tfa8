@extends('layouts.app')

@section('content')
<div class="about-container">
    <header class="about-header">
        <h1 class="page-title">About This Application</h1>
        <p class="page-lead">
            Technical Formative Assessment 7 extends Laravel Eloquent persistence with validated create and edit forms, <code>old()</code> input retention, and display-ready user avatar uploads stored on the public disk.
        </p>
    </header>

    <section class="content-section" aria-labelledby="flow-heading">
        <h2 id="flow-heading" class="section-title">Request Execution Flow</h2>
        <p class="section-lead">
            In Laravel, form submissions pass through request validation, image preparation, Eloquent ORM persistence, and Blade compilation:
        </p>

        <ol class="flow-list">
            <li class="flow-item">
                <div class="flow-marker">1</div>
                <div class="flow-content">
                    <h3 class="flow-step-title">Route &amp; CSRF Verification</h3>
                    <p class="flow-step-text">HTTP <code>POST</code> and <code>PUT</code> requests hit <code>routes/web.php</code> resource routes protected by CSRF tokens and route-model binding.</p>
                </div>
            </li>

            <li class="flow-item">
                <div class="flow-marker">2</div>
                <div class="flow-content">
                    <h3 class="flow-step-title">Form Request Validation</h3>
                    <p class="flow-step-text"><code>SaveCustomerRequest</code> and <code>SaveUserRequest</code> validate required fields, email/username uniqueness, and image constraints (&le; 2 MB). Failures redirect back with <code>old()</code> input and <code>$errors</code>.</p>
                </div>
            </li>

            <li class="flow-item">
                <div class="flow-marker">3</div>
                <div class="flow-content">
                    <h3 class="flow-step-title">Image Preparation &amp; Eloquent Storage</h3>
                    <p class="flow-step-text"><code>AvatarService</code> crops uploaded profile photos to 256&times;256 with Intervention Image, writes them to <code>Storage::disk('public')</code>, and stores only the relative path in <code>users.avatar</code>.</p>
                </div>
            </li>

            <li class="flow-item">
                <div class="flow-marker">4</div>
                <div class="flow-content">
                    <h3 class="flow-step-title">Redirect &amp; View Presentation</h3>
                    <p class="flow-step-text">After persistence succeeds, old replaced avatars are cleaned up and the browser redirects with a flash status banner rendered inside the Blade directory ledger.</p>
                </div>
            </li>
        </ol>
    </section>

    <section class="content-section" aria-labelledby="comparison-heading">
        <h2 id="comparison-heading" class="section-title">Data Architecture: Validation &amp; Public Storage</h2>
        <p class="section-lead">
            How Form Requests and <code>Storage::disk('public')</code> integrate cleanly with <code>App\Models\Customer</code> and <code>App\Models\User</code>:
        </p>

        <div class="comparison-grid">
            <div class="code-column">
                <h3 class="code-title">Request Validation (FormRequest)</h3>
                <pre class="code-block" role="region" aria-label="Form request validation example" tabindex="0"><code>// SaveUserRequest.php
return [
    'username' => [
        'required', 'max:50',
        Rule::unique('users', 'username')->ignore($user?->id),
    ],
    'full_name' => ['required', 'string', 'max:100'],
    'avatar'    => ['nullable', 'file', 'image', 'max:2048'],
];</code></pre>
            </div>

            <div class="code-column">
                <h3 class="code-title">Avatar Preparation (AvatarService)</h3>
                <pre class="code-block" role="region" aria-label="Avatar storage service example" tabindex="0"><code>// AvatarService.php
$image = Image::decode($file)->cover(256, 256);
$path  = 'avatars/' . Str::uuid() . '.' . $ext;

Storage::disk('public')->put($path, (string) $encoded);
$user->update(['avatar' => $path]);</code></pre>
            </div>
        </div>
    </section>
</div>
@endsection
