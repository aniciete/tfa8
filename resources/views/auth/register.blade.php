<x-guest-layout>
    <div class="auth-card">
        <header class="form-card-header">
            <h1 class="form-card-title">Register Operator Account</h1>
            <span class="form-card-docket">NEW ACCESS</span>
        </header>

        <div class="auth-card-body">
            <p class="auth-subtitle">
                Create a new Laravel Breeze authenticated operator account. Your POS username handle is generated automatically.
            </p>

            @include('partials.form_errors')

            <form method="POST" action="{{ route('register') }}" class="ledger-form auth-form">
                @csrf

                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label for="name" class="form-label">
                        <span>Full Name</span>
                        <span class="req-mark" aria-hidden="true">*</span>
                    </label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        enterkeyhint="next"
                        maxlength="100"
                        placeholder="e.g. Marco Diaz"
                        required
                        autofocus
                        aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                        @error('name') aria-describedby="name_error" @enderror
                        data-error="name_error"
                    >
                    <p id="name_error" class="field-error-msg">
                        @error('name')
                            <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                        @else
                            <span class="err-tag" aria-hidden="true">Error:</span> Full name is required.
                        @enderror
                    </p>
                </div>

                <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                    <label for="email" class="form-label">
                        <span>Email Address</span>
                        <span class="req-mark" aria-hidden="true">*</span>
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        autocorrect="off"
                        autocapitalize="none"
                        spellcheck="false"
                        enterkeyhint="next"
                        maxlength="150"
                        placeholder="e.g. marco.diaz@example.com"
                        required
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        @error('email') aria-describedby="email_error" @enderror
                        data-error="email_error"
                    >
                    <p id="email_error" class="field-error-msg">
                        @error('email')
                            <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                        @else
                            <span class="err-tag" aria-hidden="true">Error:</span> Enter a valid email address.
                        @enderror
                    </p>
                </div>

                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password" class="form-label">
                        <span>Password</span>
                        <span class="req-mark" aria-hidden="true">*</span>
                    </label>
                    <p id="password_help" class="field-help">Minimum 8 characters.</p>
                    <div class="password-input-wrap">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control"
                            autocomplete="new-password"
                            enterkeyhint="next"
                            required
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                            aria-describedby="{{ $errors->has('password') ? 'password_help password_error' : 'password_help' }}"
                            data-error="password_error"
                        >
                        <button
                            type="button"
                            class="password-toggle-btn"
                            data-password-toggle="password"
                            aria-pressed="false"
                            aria-label="Show password"
                        >Show</button>
                    </div>
                    <p id="password_error" class="field-error-msg">
                        @error('password')
                            <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                        @else
                            <span class="err-tag" aria-hidden="true">Error:</span> Choose a password with at least 8 characters.
                        @enderror
                    </p>
                </div>

                <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <label for="password_confirmation" class="form-label">
                        <span>Confirm Password</span>
                        <span class="req-mark" aria-hidden="true">*</span>
                    </label>
                    <div class="password-input-wrap">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            autocomplete="new-password"
                            enterkeyhint="done"
                            required
                            aria-invalid="{{ $errors->has('password_confirmation') ? 'true' : 'false' }}"
                            @error('password_confirmation') aria-describedby="password_confirmation_error" @enderror
                            data-error="password_confirmation_error"
                        >
                        <button
                            type="button"
                            class="password-toggle-btn"
                            data-password-toggle="password_confirmation"
                            aria-pressed="false"
                            aria-label="Show confirm password"
                        >Show</button>
                    </div>
                    <p id="password_confirmation_error" class="field-error-msg">
                        @error('password_confirmation')
                            <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                        @else
                            <span class="err-tag" aria-hidden="true">Error:</span> Re-enter the same password.
                        @enderror
                    </p>
                </div>

                <div class="form-actions auth-actions">
                    <button type="submit" class="button button-primary">Create Account</button>
                    <a href="{{ route('login') }}" class="button button-secondary">Already Registered?</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
