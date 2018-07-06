<div class="card mb-4">
    <div class="card-header">
        Description
    </div>
    <div class="card-body">
        <p class="card-text">{{ $token->content }}</p>
        <div class="row">
            <div class="col-sm-2 col-4">
                <i class="{{ $token->divisible ? 'fa fa-check text-success' : 'fa fa-times text-danger' }}"></i> Divisible
            </div>
            <div class="col-sm-2 col-4">
                <i class="{{ $token->locked ? 'fa fa-check text-success' : 'fa fa-times text-danger' }}"></i> Locked
            </div>
            <div class="col-sm-2 col-4">
                <a href="{{ $token->explorer_url }}" class="card-link" target="_blank"><i class="fa fa-external-link-square"></i> Explorer</a>
            </div>
        </div>
    </div>
</div>