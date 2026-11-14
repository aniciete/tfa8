@if ($errors->any())
    <div id="form-error-summary" class="form-error-summary" role="alert" tabindex="-1" aria-labelledby="error-summary-heading">
        <div class="summary-header">
            <span class="summary-icon" aria-hidden="true">!</span>
            <h2 id="error-summary-heading" class="summary-title">
                Please resolve the following {{ $errors->count() === 1 ? 'error' : 'errors' }}:
            </h2>
        </div>
        <ul class="summary-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
