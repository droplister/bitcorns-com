<a class="btn btn-primary float-right mt-3" href="{{ url(route('groups.create')) }}">
    Create
</a>
<h1 class="display-4 mt-5 mb-5">
    <span class="d-none d-sm-inline">Farming</span> Coops
    <small class="lead d-none d-sm-inline">{{ $groups->count() }} Found</small>
</h1>
@include('partials.session')