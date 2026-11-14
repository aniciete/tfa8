<section class="form-card" aria-labelledby="customer-form-heading">
    <header class="form-card-header">
        <h2 id="customer-form-heading" class="form-card-title">
            {{ $mode === 'create' ? 'Customer Registration Details' : 'Customer Profile Details' }}
        </h2>
        <span class="form-card-docket tabular-num">
            {{ $mode === 'create' ? 'NEW RECORD' : 'ID #' . sprintf('%02d', $customer->id) }}
        </span>
    </header>

    <form action="{{ $action }}" method="POST" class="ledger-form">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="form-group {{ $errors->has('full_name') ? 'has-error' : '' }}">
            <label for="full_name" class="form-label">
                <span>Full Name</span>
                <span class="req-mark" aria-hidden="true">*</span>
            </label>
            <p id="full_name_help" class="field-help">Customer's full personal or trade name (up to 100 characters).</p>
            <input
                type="text"
                id="full_name"
                name="full_name"
                class="form-control"
                value="{{ old('full_name', $customer->full_name) }}"
                autocomplete="name"
                maxlength="100"
                placeholder="e.g. Elena Rostova"
                required
                aria-invalid="{{ $errors->has('full_name') ? 'true' : 'false' }}"
                aria-describedby="{{ $errors->has('full_name') ? 'full_name_help full_name_error' : 'full_name_help' }}"
                data-error="full_name_error"
            >
            <p id="full_name_error" class="field-error-msg">
                @error('full_name')
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
            <p id="email_help" class="field-help">Unique email address in the format you@example.com.</p>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                value="{{ old('email', $customer->email) }}"
                autocomplete="email"
                inputmode="email"
                autocorrect="off"
                autocapitalize="none"
                spellcheck="false"
                maxlength="100"
                placeholder="e.g. elena.rostova@example.com"
                required
                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                aria-describedby="{{ $errors->has('email') ? 'email_help email_error' : 'email_help' }}"
                data-error="email_error"
            >
            <p id="email_error" class="field-error-msg">
                @error('email')
                    <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                @else
                    <span class="err-tag" aria-hidden="true">Error:</span> Enter a valid email address (for example, name@example.com).
                @enderror
            </p>
        </div>

        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
            <label for="phone" class="form-label">
                <span>Phone Number</span>
                <span class="optional-tag">(Optional)</span>
            </label>
            <p id="phone_help" class="field-help">Contact number for receipts or order updates (up to 20 characters).</p>
            <input
                type="tel"
                id="phone"
                name="phone"
                class="form-control"
                value="{{ old('phone', $customer->phone) }}"
                autocomplete="tel"
                inputmode="tel"
                maxlength="20"
                placeholder="e.g. +1 (555) 234-5678"
                aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}"
                aria-describedby="{{ $errors->has('phone') ? 'phone_help phone_error' : 'phone_help' }}"
                data-error="phone_error"
            >
            <p id="phone_error" class="field-error-msg">
                @error('phone')
                    <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                @else
                    <span class="err-tag" aria-hidden="true">Error:</span> Phone number must not exceed 20 characters.
                @enderror
            </p>
        </div>

        @if ($mode === 'edit' && $customer->created_at)
            <div class="form-meta-note">
                <span class="meta-label">Registered:</span>
                <span class="meta-value cell-mono tabular-num">{{ $customer->created_at->format('Y-m-d H:i') }}</span>
            </div>
        @endif

        <div class="form-actions">
            <button type="submit" class="button button-primary">
                {{ $mode === 'create' ? 'Save Customer' : 'Update Customer' }}
            </button>
            <a href="{{ route('customers.index') }}" class="button button-secondary">Cancel</a>
        </div>
    </form>
</section>
