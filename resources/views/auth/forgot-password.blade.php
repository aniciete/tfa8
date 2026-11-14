<x-guest-layout>
    <div class="auth-card">
        <header class="form-card-header">
            <h1 class="form-card-title">Reset Password Request</h1>
            <span class="form-card-docket">RECOVERY</span>
        </header>

        <div class="auth-card-body">
            <p class="auth-subtitle">
                Enter your registered email address and Laravel will send a password reset link if mail transport is configured.
            </p>

            @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
            @endif

            @include('partials.form_errors')

            <form method="POST" action="{{ route('password.email') }}" class="ledger-form auth-form">
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
                        required
                        autofocus
                    >
                    <p class="field-error-msg">
                        @error('email')
                            <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="form-actions auth-actions">
                    <button type="submit" class="button button-primary">Send Password Reset Link</button>
                    <a href="{{ route('login') }}" class="button button-secondary">Back to Sign In</a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
