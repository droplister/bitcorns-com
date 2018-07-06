<h2 class="mt-3 mb-3">{{ $upload->player->name }}</h2>
<div class="row">
    <div class="col">
        <h4>New Image:</h4>
        <a href="{{ url(route('uploads.index')) }}/accept"
            onclick="event.preventDefault();
            document.getElementById('accept-image-{{ $loop->index }}').submit();">
            <img src="{{ $upload->new_image_url }}" class="mt-3 mb-3 img-thumbnail" />
        </a>
        <form id="accept-image-{{ $loop->index }}" action="{{ url(route('uploads.update', ['upload' => $upload->id])) }}" method="POST" style="display: none;">
            <input type="hidden" name="_method" value="PUT">
            {{ csrf_field() }}
            <input type="hidden" name="action" value="accept" />
        </form>
    </div>
    <div class="col">
        <h4>Old Image:</h4>
        <a href="{{ url(route('uploads.index')) }}/reject"
            onclick="event.preventDefault();
            document.getElementById('reject-image-{{ $loop->index }}').submit();">
            <img src="{{ $upload->old_image_url }}" class="mt-3 mb-3 img-thumbnail" />
        </a>
        <form id="reject-image-{{ $loop->index }}" action="{{ url(route('uploads.update', ['upload' => $upload->id])) }}" method="POST" style="display: none;">
            <input type="hidden" name="_method" value="PUT">
            {{ csrf_field() }}
            <input type="hidden" name="action" value="reject" />
        </form>
    </div>
</div>
<hr />