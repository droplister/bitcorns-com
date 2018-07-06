<h5 class="card-title d-none d-md-block"><a href="{{ url(route('cards.show', ['token' => $card->display_name])) }}" class="text-dark">{{ $card->display_name }}</a></h5>
<h6 class="card-title d-block d-md-none"><a href="{{ url(route('cards.show', ['token' => $card->display_name])) }}" class="text-dark">{{ $card->display_name }}</a></h6>
<p class="card-text d-none d-md-block">Issuance: {{ $card->normalized_total }}</p>
<a href="{{ url(route('cards.show', ['token' => $card->name])) }}"><img src="{{ str_replace('tokens', 'cards', $card->image_url) }}" width="100%" /></a>
<h6 class="card-title mt-3 d-block d-md-none">x {{ $card->normalized_total }}</h6>