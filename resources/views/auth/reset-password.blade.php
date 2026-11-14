<x-guest-layout>
    <div class="auth-card">
        <header class="form-card-header">
            <h1 class="form-card-title">Choose New Password</h1>
            <span class="form-card-docket">RESET</span>
        </header>

        <div class="auth-card-body">
            @include('partials.form_errors')

            <form method="POST" action="{{ route('password.store') }}" class="ledger-form auth-form">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
                        value="{{ old('email', $request->email) }}"
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

                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label for="password" class="form-label">
                        <span>New Password</span>
                        <span class="req-mark" aria-hidden="true">*</span>
                    </label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control"
                        autocomplete="new-password"
                        required
                    >
                    <p class="field-error-msg">
                        @error('password')
                            <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <label for="password_confirmation" class="form-label">
                        <span>Confirm Password</span>
                        <span class="req-mark" aria-hidden="true">*</span>
                    </label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        autocomplete="new-password"
                        required
                    >
                    <p class="field-error-msg">
                        @error('password_confirmation')
                            <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="form-actions auth-actions">
                    <button type="submit" class="button button-primary">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
