<x-guest-layout>
    <div class="auth-card">
        <header class="form-card-header">
            <h1 class="form-card-title">Operator Sign In</h1>
            <span class="form-card-docket">AUTH GUARD</span>
        </header>

        <div class="auth-card-body">
            <p class="auth-subtitle">
                Sign in with your staff account email and password to unlock Customer and User account management.
            </p>

            @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
            @endif

            @include('partials.form_errors')

            <form method="POST" action="{{ route('login') }}" class="ledger-form auth-form">
                @csrf

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
                        placeholder="e.g. admin.reyes@example.com"
                        required
                        autofocus
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                        @error('email') aria-describedby="email_error" @enderror
                        data-error="email_error"
                    >
                    <p id="email_error" class="field-error-msg">
                        @error('email')
                            <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                        @else
                            <span class="err-tag" aria-hidden="true">Error:</span> Enter a valid staff email address.
                        @enderror
                    </p>
                </div>

                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password" class="form-label">
                        <span>Password</span>
                        <span class="req-mark" aria-hidden="true">*</span>
                    </label>
                    <div class="password-input-wrap">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-control"
                            autocomplete="current-password"
                            enterkeyhint="done"
                            required
                            aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                            @error('password') aria-describedby="password_error" @enderror
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
                            <span class="err-tag" aria-hidden="true">Error:</span> Password is required.
                        @enderror
                    </p>
                </div>

                <div class="auth-meta-row">
                    <label for="remember_me" class="checkbox-inline">
                        <input id="remember_me" type="checkbox" name="remember" class="checkbox-input" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkbox-label">Keep me signed in</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="table-link">Forgot password?</a>
                    @endif
                </div>

                <div class="form-actions auth-actions">
                    <button type="submit" class="button button-primary">Sign In</button>
                    <a href="{{ route('register') }}" class="button button-secondary">Create Account</a>
                </div>
            </form>

            <aside class="auth-demo-note" aria-label="Seeded test account details">
                <strong>Seeded Staff Account:</strong>
                <code>admin.reyes@example.com</code> &bull; Default seeder password: <code>password</code>
            </aside>
        </div>
    </div>
</x-guest-layout>
