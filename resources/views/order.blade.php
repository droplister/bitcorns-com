@extends('layouts.app')

@section('title', 'Order Form')

@section('content')
<div class="container">
    <h1 class="display-4 mt-5 mb-5" style="white-space: nowrap;">
        <img src="https://bitcorns.com/img/tokens/thumbs/CROPS.png" class="float-left mr-3" />
        Buy CROPS <small class="lead d-none d-sm-inline">Establish a Farm</small>
    </h1>
    <div class="card mb-4">
        <div class="card-body">
            <iframe height="1500"
                allowTransparency="true"
                frameborder="0"
                scrolling="no"
                style="width:100%;border:none"
                src="https://bitcorns.wufoo.com/embed/z1ywmu5t0saod7n/">
            <a href="https://bitcorns.wufoo.com/forms/z1ywmu5t0saod7n/">
                Fill out my Wufoo form!
            </a>
            </iframe>
        </div>
    </div>
</div>
@endsection