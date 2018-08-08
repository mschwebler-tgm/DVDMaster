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
    <script src="/js/app.js" defer></script>
    <script src="/lib/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js" defer></script>
    <script src="/lib/typeahead/typeahead.js" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic|Roboto+Mono:400,500|Material+Icons" rel="stylesheet">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link href="/lib/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.css" rel="stylesheet">
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
    <root>
        <template slot="topRight">
            @guest
                <span class="md-subheading pointer" style="line-height: 48px; vertical-align: center" @click="toLogin">{{ __('Login') }}</span>&nbsp;&nbsp;&nbsp;
                <span class="md-subheading pointer" style="line-height: 48px; vertical-align: center" @click="toRegistration">{{ __('Register') }}</span>
            @else
                <md-button @click="showSidepanel = true">{{ isset(Auth::user()->name) ? Auth::user()->name : 'User' }}</md-button>
            @endguest
        </template>
        <template slot="content">
            @yield('content')
        </template>
    </root>
    <md-drawer class="md-right" style="max-width: 300px;" :md-active.sync="showSidepanel">
        <md-toolbar class="md-transparent" md-elevation="0">
            <span class="md-title">{{ isset(Auth::user()->name) ? Auth::user()->name : 'User' }}</span>
        </md-toolbar>

        <md-list>
            <md-list-item class="pointer">
                <md-icon>power_settings_new</md-icon>
                <span class="md-list-item-text" onclick="document.getElementById('logout-form').submit();">Logout</span>
            </md-list-item>
        </md-list>
    </md-drawer>

    <form id="logout-form" action="/logout" method="POST"
          style="display: none;">
        @csrf
    </form>
</div>

<script>
    window.user_id = {{ isset(Auth::user()->id) ? Auth::user()->id : 0 }};
</script>
</body>
</html>
