<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                Start a Cooperative
            </div>
            <div class="card-body">
                <p class="card-text">Given that they have <a href="{{ url(route('tokens.show', ['token' => 'CROPS'])) }}">0.1 CROPS</a>, farms can start cooperatives and cummulatively rank.</p>
                <hr class="mt-4 mb-4" />
                @include('groups.partials.create.form')
            </div>
        </div>
    </div>
</div>