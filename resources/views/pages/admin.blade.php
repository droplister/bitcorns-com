@extends('layouts.app')

@section('title', 'Admin')

@section('content')
<div class="container">
  <h1 class="display-4 mt-5 mb-4">Admin</h1>
  <div class="row">
    <div class="col">
      <a href="{{ url(route('uploads.index')) }}" class="btn btn-primary btn-lg btn-block">Moderate Uploads</a>
    </div>
  </div>
</div>
@endsection