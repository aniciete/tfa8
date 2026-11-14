@php
    $selectedRole = old('role', $user->role ?: 'Staff Member');
    $hasAvatar = $user->exists && $user->hasAvatar();
    $previewSrc = $hasAvatar
        ? $user->avatarUrl()
        : preg_replace('~^https?:~', '', asset('assets/images/avatar-placeholder.svg'));
@endphp

<section class="form-card" aria-labelledby="user-form-heading">
    <header class="form-card-header">
        <h2 id="user-form-heading" class="form-card-title">
            {{ $mode === 'create' ? 'Staff Account Credentials' : 'Update Staff Account' }}
        </h2>
        <span class="form-card-docket tabular-num">
            {{ $mode === 'create' ? 'NEW OPERATOR' : 'ID #' . sprintf('%02d', $user->id) }}
        </span>
    </header>

    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="ledger-form">
        @csrf
        @if ($method !== 'POST')
            @method($method)
        @endif

        <div class="form-group {{ $errors->has('full_name') ? 'has-error' : '' }}">
            <label for="full_name" class="form-label">
                <span>Full Name</span>
                <span class="req-mark" aria-hidden="true">*</span>
            </label>
            <p id="full_name_help" class="field-help">Operator's full display name (up to 100 characters).</p>
            <input
                type="text"
                id="full_name"
                name="full_name"
                class="form-control"
                value="{{ old('full_name', $user->full_name) }}"
                autocomplete="name"
                maxlength="100"
                placeholder="e.g. Carlos Reyes"
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

        <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
            <label for="username" class="form-label">
                <span>Username</span>
                <span class="req-mark" aria-hidden="true">*</span>
            </label>
            <p id="username_help" class="field-help">Unique login handle using letters, numbers, periods, hyphens, or underscores.</p>
            <input
                type="text"
                id="username"
                name="username"
                class="form-control cell-mono"
                value="{{ old('username', $user->username) }}"
                autocomplete="username"
                autocorrect="off"
                autocapitalize="none"
                spellcheck="false"
                maxlength="50"
                pattern="[A-Za-z0-9._\-]+"
                placeholder="e.g. cashier.cruz"
                required
                aria-invalid="{{ $errors->has('username') ? 'true' : 'false' }}"
                aria-describedby="{{ $errors->has('username') ? 'username_help username_error' : 'username_help' }}"
                data-error="username_error"
            >
            <p id="username_error" class="field-error-msg">
                @error('username')
                    <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                @else
                    <span class="err-tag" aria-hidden="true">Error:</span> Enter a unique username using letters, numbers, periods, hyphens, or underscores.
                @enderror
            </p>
        </div>

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email" class="form-label">
                <span>Login Email</span>
                <span class="optional-tag">(Optional &bull; Needed for Sign In)</span>
            </label>
            <p id="email_help" class="field-help">Assign a unique email address so this operator can authenticate via Breeze Sign In.</p>
            <input
                type="email"
                id="email"
                name="email"
                class="form-control"
                value="{{ old('email', $user->email) }}"
                autocomplete="email"
                autocorrect="off"
                autocapitalize="none"
                spellcheck="false"
                maxlength="150"
                placeholder="e.g. cashier.cruz@example.com"
                aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                aria-describedby="{{ $errors->has('email') ? 'email_help email_error' : 'email_help' }}"
                data-error="email_error"
            >
            <p id="email_error" class="field-error-msg">
                @error('email')
                    <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                @else
                    <span class="err-tag" aria-hidden="true">Error:</span> Enter a valid, unique email address.
                @enderror
            </p>
        </div>

        <div class="form-row-two">
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                <label for="password" class="form-label">
                    <span>{{ $mode === 'create' ? 'Password' : 'New Password' }}</span>
                    <span class="optional-tag">({{ $mode === 'create' ? 'Optional' : 'Leave blank to keep current' }})</span>
                </label>
                <div class="password-input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        autocomplete="new-password"
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
                        <span class="err-tag" aria-hidden="true">Error:</span> Password must be at least 8 characters.
                    @enderror
                </p>
            </div>

            <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                <label for="password_confirmation" class="form-label">
                    <span>Confirm Password</span>
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    autocomplete="new-password"
                >
            </div>
        </div>

        <fieldset class="form-group role-fieldset {{ $errors->has('role') ? 'has-error' : '' }}">
            <legend class="form-label">System Role</legend>
            <p id="role_help" class="field-help">Assign one operational permission role for this terminal account.</p>
            <div class="role-radio-grid">
                @foreach ($roles as $idx => $roleOption)
                    @php
                        $roleId = 'role_opt_' . $idx;
                    @endphp
                    <div class="role-radio-option">
                        <input
                            type="radio"
                            id="{{ $roleId }}"
                            name="role"
                            value="{{ $roleOption }}"
                            class="role-radio-input"
                            aria-describedby="role_help"
                            {{ $selectedRole === $roleOption ? 'checked' : '' }}
                        >
                        <label for="{{ $roleId }}" class="role-radio-label">{{ $roleOption }}</label>
                    </div>
                @endforeach
            </div>
            @error('role')
                <p id="role_error" class="field-error-msg field-error-visible">
                    <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                </p>
            @enderror
        </fieldset>

        <div class="form-group {{ $errors->has('avatar') ? 'has-error' : '' }}">
            <label for="avatar" class="form-label">
                <span>Profile Picture</span>
                <span class="optional-tag">(Optional &bull; Image &le; 2 MB)</span>
            </label>
            <p id="avatar_help" class="field-help">Upload a JPG, PNG, GIF, BMP, or WebP file up to 2 MB. Prepared to a 256&times;256 thumbnail on the public disk.</p>

            <div class="avatar-preview-box">
                <img
                    id="avatar-live-preview"
                    src="{{ $previewSrc }}"
                    data-default-src="{{ $previewSrc }}"
                    alt="{{ $hasAvatar ? $user->full_name . ' current avatar' : 'Default profile avatar placeholder' }}"
                    class="avatar-preview-img"
                    width="72"
                    height="72"
                >
                <div class="avatar-preview-meta">
                    <p id="avatar-preview-status" class="avatar-preview-title">
                        {{ $hasAvatar ? 'Current Saved Avatar' : 'Placeholder Avatar Active' }}
                    </p>
                    <p id="avatar-preview-filename" class="avatar-preview-subtext cell-mono">
                        {{ $hasAvatar ? $user->avatar : 'No custom image uploaded yet' }}
                    </p>
                    @if ($hasAvatar)
                        <div class="avatar-remove-row">
                            <input type="checkbox" id="remove_avatar" name="remove_avatar" value="1" class="checkbox-input" {{ old('remove_avatar') ? 'checked' : '' }}>
                            <label for="remove_avatar" class="checkbox-label">Remove current avatar on save</label>
                        </div>
                    @endif
                </div>
            </div>

            <input
                type="file"
                id="avatar"
                name="avatar"
                class="form-control form-file-control"
                accept="image/jpeg,image/png,image/webp,image/gif"
                aria-invalid="{{ $errors->has('avatar') ? 'true' : 'false' }}"
                aria-describedby="{{ $errors->has('avatar') ? 'avatar_help avatar_error' : 'avatar_help' }}"
                data-error="avatar_error"
            >
            <p id="avatar_error" class="field-error-msg">
                @error('avatar')
                    <span class="err-tag" aria-hidden="true">Error:</span> {{ $message }}
                @else
                    <span class="err-tag" aria-hidden="true">Error:</span> Select a valid image file no larger than 2 MB.
                @enderror
            </p>
        </div>

        @if ($mode === 'edit' && $user->created_at)
            <div class="form-meta-note">
                <span class="meta-label">Account Created:</span>
                <span class="meta-value cell-mono tabular-num">{{ $user->created_at->format('Y-m-d H:i') }}</span>
            </div>
        @endif

        <div class="form-actions">
            <button type="submit" class="button button-primary">
                {{ $mode === 'create' ? 'Save User' : 'Update User' }}
            </button>
            <a href="{{ route('users.index') }}" class="button button-secondary">Cancel</a>
        </div>
    </form>
</section>
