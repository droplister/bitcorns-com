<h5 class="card-title d-none d-sm-block"><a href="{{ url(route('cards.show', ['token' => $upgrade->token->name])) }}" class="text-dark">{{ $upgrade->token->display_name }}</a></h5>
<h6 class="card-title d-block d-sm-none"><a href="{{ url(route('cards.show', ['token' => $upgrade->token->name])) }}" class="text-dark">{{ $upgrade->token->display_name }}</a></h6>
<p class="card-text d-none d-sm-block">Issuance: {{ number_format($upgrade->token->total_issued) }}</p>
<p class="card-text"><a href="{{ url(route('cards.show', ['token' => $upgrade->token->name])) }}"><img src="{{ str_replace('tokens', 'cards', $upgrade->token->image_url) }}" width="100%" /></a></p>
<h6 class="card-title mt-3">x {{ $upgrade->display_quantity }}</h6>