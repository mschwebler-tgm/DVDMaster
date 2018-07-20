<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'DVD Master') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('lib/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js') }}" defer></script>
    <script src="{{ asset('lib/typeahead/typeahead.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/js/materialize.min.js"></script>
    <script src="https://materializecss.com/extras/noUiSlider/nouislider.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic|Roboto+Mono:400,500|Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="https://materializecss.com/extras/noUiSlider/nouislider.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <div id="loadingscreen" v-if="showLoading">
        <div class="preloader-wrapper active">
            <div class="spinner-layer spinner-red-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div><div class="gap-patch">
                    <div class="circle"></div>
                </div><div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar-fixed">
        @guest
        @else
            <ul id="dropdown1" class="dropdown-content">
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                          style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        @endguest

        <nav>
            <div class="nav-wrapper">
                <a class="brand-logo left" href="{{ url('/') }}" style="padding-left: 20px;">
                    {{ config('app.name', 'DVD Master') }}
                </a>
                <form class="container" >
                    <div class="input-field right" style="width: 400px; background-color: #CC444B;">
                        <input id="search" type="search" style="height: 64px !important" required>
                        <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                        <i class="material-icons">close</i>
                    </div>
                </form>
                <ul class="right hide-on-med-and-down" style="position: absolute; top: 0; right: 0;">
                    <!-- Dropdown Trigger -->
                    @guest
                        <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                        <li><a href="{{ route('register') }}">{{ __('Register') }}</a></li>
                    @else
                        <li><a class="dropdown-trigger" href="#" data-target="dropdown1">{{ isset(Auth::user()->name) ? Auth::user()->name : 'User' }}<i
                                        class="material-icons right">arrow_drop_down</i></a></li>
                    @endguest
                </ul>
            </div>
        </nav>
    </div>

    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
