@extends('layouts.app')

@section('title', 'World Map')

@section('description', 'Where in the world are people farming bitcorn?')

@section('content')
  <google-map v-bind:lat="39.828175" v-bind:lng="-98.5795" v-bind:zoom="2"></google-map>
  <div class="container">
      <div class="row">
          <div class="col-sm-4 mt-5">
              <h3>Total Farms: {{ \App\Player::whereHasAccess()->count() }}</h3>
              <span class="d-block lead">
                <a href="{{ env('TELEGRAM') }}" target="_blank">Join Telegram Chat <i class="fa fa-external-link"></i></a>
              </span>
          </div>
          <div class="col-sm-4 mt-5">
              <h3>On The Map: {{ \App\Player::whereNotNull('latitude')->whereHasAccess()->count() }}</h3>
              <span class="d-block lead">
                <a href="{{ url(route('tokens.index')) }}">Requires Access Token &raquo;</a>
              </span>
          </div>
          <div class="col-sm-4 mt-5">
              <h3>Harvest: April 1<sup>st</sup></h3>
              <span class="d-block lead">
                <a href="{{ url(route('almanac')) }}">The Bitcorn Almanac &raquo;</a>
              </span>
          </div>
      </div>
  </div>
@endsection