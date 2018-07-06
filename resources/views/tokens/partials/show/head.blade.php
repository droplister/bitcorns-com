<h1 class="display-4 mt-5 mb-5">
    {{ $token->display_name }} <small class="lead d-none d-sm-inline">{{ ucfirst($token->type) }} Token</small>
    <img src="{{ $token->display_thumb_url }}" class="float-left mr-3" height="60" />
</h1>