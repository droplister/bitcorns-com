<div class="content">
    <div class="container">
        @include('partials.session')
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        @include('players.partials.edit.menu', ['tab' => 'image'])
                    </div>
                    <div class="card-body">
                        @include('uploads.partials.create.form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>