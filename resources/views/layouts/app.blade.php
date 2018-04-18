<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="format-detection" content="telephone=no">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
@yield('meta')

  <!-- Title Tags -->
  <title>@yield('title') &ndash; {{ config('app.name', 'Laravel') }}</title>
  <meta name="description" content="@yield('description')">

  <!-- Stylesheets -->
  <link href="{{ asset('favicon.ico') }}" rel="icon">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <!--
   /$$       /$$   /$$                                            
  | $$      |__/  | $$                                            
  | $$$$$$$  /$$ /$$$$$$    /$$$$$$$  /$$$$$$   /$$$$$$  /$$$$$$$ 
  | $$__  $$| $$|_  $$_/   /$$_____/ /$$__  $$ /$$__  $$| $$__  $$
  | $$  \ $$| $$  | $$    | $$      | $$  \ $$| $$  \__/| $$  \ $$
  | $$  | $$| $$  | $$ /$$| $$      | $$  | $$| $$      | $$  | $$
  | $$$$$$$/| $$  |  $$$$/|  $$$$$$$|  $$$$$$/| $$      | $$  | $$
  |_______/ |__/   \___/   \_______/ \______/ |__/      |__/  |__/
  -->
</head>
<body>
<div id="app">
  <header>
    <div class="collapse bg-dark" id="navbarHeader">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-8 py-4">
            <h4 class="text-white">Bitcorn Crops</h4>
            <p class="text-muted">Bitcorns is an idle game of accumulation, similar to AdVenture Capitalist, where the only objective is to accumulate BITCORN. BITCORN cannot be bought, rather, it gets harvested by bitcoin addresses ("farms") proportionate to their share of 100 CROPS. Deceptively simple, accumulating BITCORN takes an amount of restraint most people do not possess.</p>
          </div>
          <div class="col-sm-4 py-4 d-none d-sm-inline">
            <h4 class="text-white">Contact</h4>
            <ul class="list-unstyled">
              <li><a href="{{ env('TELEGRAM') }}" class="text-white" target="_blank">Telegram</a></li>
              <li><a href="{{ env('TWITTER') }}" class="text-white" target="_blank">Twitter</a></li>
              <li><a href="mailto:{{ env('EMAIL') }}" class="text-white">E-mail</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="navbar navbar-dark navbar-expand bg-dark">
      <div class="container-fluid d-flex justify-content-between">
        <a href="{{ url('/') }}" class="navbar-brand">&#x1f33d; <span class="d-none d-sm-inline">{{ config('app.name', 'Laravel') }}</span></a>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item d-none d-sm-inline">
              <a class="nav-link" href="{{ url('/') }}">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('players.index')) }}">Farms</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('groups.index')) }}">Coops</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="almanac_dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Pages</a>
              <div class="dropdown-menu" aria-labelledby="almanac_dropdown">
                <a class="dropdown-item" href="{{ url(route('almanac')) }}">Almanac</a>
                <a class="dropdown-item" href="{{ url(route('map')) }}">World Map</a>
                <a class="dropdown-item" href="{{ url('/rules') }}">Game Rules</a>
                <a class="dropdown-item" href="{{ url(route('tokens.index')) }}">Game Tokens</a>
                <a class="dropdown-item" href="{{ url('/submit') }}">Submit Assets</a>
                <a class="dropdown-item" href="https://medium.com/@BitcornCrops" target="_blank">News &amp; Updates</a>
              </div>
            </li>
            @if(Auth::check())
            <li class="nav-item ml-4">
              <a class="nav-link" href="{{ url('/logout') }}"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out"></i> Logout
              </a>
              <form id="logout-form" action="{{ url(route('logout')) }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
            </li>
            @endif
          </ul>
        </div>
        <form action="{{ url(route('players.index')) }}" method="GET" class="form-inline my-2 my-lg-0 d-none d-md-inline">
          <input class="form-control mr-sm-2" name="q" type="search" placeholder="Search" aria-label="Search">
        </form>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>
    </div>
  </header>

  <main role="main">
    @yield('content')
  </main>

  <footer class="text-muted">
    <div class="container">
      <p class="float-right">
        <a href="#">Top ^</a>
      </p>
      <p>
        <a href="mailto:bitcorncrops@gmail.com" class="mr-3">Contact</a>
        <a href="{{ env('GITHUB') }}" target="_blank" class="mr-3">Github</a>
        <a href="{{ url('/privacy') }}" class="mr-3">Privacy</a>
        <a href="{{ url('/terms') }}">Terms</a>
      </p>
    </div>
  </footer>

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112477384-4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-112477384-4');
</script>
</body>
</html>