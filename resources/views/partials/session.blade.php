@if (session('error'))
    <div class="alert alert-warning mb-4" role="alert">
        Error: {{ session('error') }}
    </div>
@elseif (session('success'))
    <div class="alert alert-success mb-4" role="alert">
        Success: {{ session('success') }}
    </div>
@endif