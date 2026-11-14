@if (session('success'))
    <div class="flash-banner flash-success" role="status" aria-live="polite">
        <span class="flash-badge" aria-hidden="true">OK</span>
        <p class="flash-message">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="flash-banner flash-error" role="alert">
        <span class="flash-badge" aria-hidden="true">!</span>
        <p class="flash-message">{{ session('error') }}</p>
    </div>
@endif
