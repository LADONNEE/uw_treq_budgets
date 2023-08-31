<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ config('app.name') }} - UW iSchool - UW</title>
    <link rel="icon" type="image/x-icon" href="/budgets/images/favicon.ico"/>
    @yield('style')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
          crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/budgets/css/app.css{{ $cacheBusting = '?v=' . config('view.resource_cache') }}" media="all" />
    <link rel="stylesheet" type="text/css" href="/style/context_menu.css{{ $cacheBusting }}" media="all" />
    <link rel="stylesheet" type="text/css" href="/budgets/css/env-warning.css{{ $cacheBusting }}" media="all" />
    <script defer src="https://kit.fontawesome.com/96343af987.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,400;0,500;0,700;1,400;1,500;1,700&display=swap" rel="stylesheet">
</head>
<body>
<div class="effort" id="vue_app">
    <div id="app" class="layout__wrapper">
    <nav class="nav-uw">
        <div class="nav-uw__menu">
            <button id="js-app-menu--trigger" class="nav-uw__btn" aria-label="Show Menu">@icon('bars')</button>
            <a href="{{ route('home') }}" class="nav-uw__home-link">Budgets</a>
        </div>
        <div class="nav-uw__search" style="display: none;">
            <button class="nav-uw__btn js-search-show" aria-label="Search">
                <div class="nav-uw__mock-search">
                    @icon('search') <span class="d-none d-sm-inline">Search</span>
                </div>
            </button>
        </div>
        <div class="nav-uw__other-menu">
            
            <img src="/treq/images/iSchoolPrimary_RGB_White.svg" height="38px" style="margin-top:4px;margin-right:24px" />

        </div>
    </nav>
    <div id="search-bar" class="layout__search" {!! (isset($searchOpen)) ? '' : 'style="display:none;"' !!}>
        @include('components/_search-bar', [ 'action' => action('SearchController@index') ])
        @yield('search')
    </div>
    <div class="layout__menu">
        @include('layout/_app-menu')
    </div>
    <div class="layout__content-main">
        <div class="container-fluid">

        @if (!isset($hasGrid))<div class="col-md-12">@endif

        @yield('content')

        @if (!isset($hasGrid))</div>@endif

        </div> <!-- /.container-fluid -->
    </div>  <!-- /#app.layout__content-main -->
    </div> <!-- /.layout__wrapper -->
    <div class="layout__footer footer">
        <div>
            <a href="mailto:itreq@uw.edu?subject=UWORG%20Website%20Question">@icon('envelope') Contact</a> &bull;
            <a href="https://uw.edu/uworg">Help</a> &bull;
            <a href="http://www.washington.edu/online/privacy">Privacy</a> &bull;
            <a href="http://www.washington.edu/online/terms">Terms</a>
        </div>
        <div class="copyright">
            <a href="https://uw.edu/uworg">&#169;{{ date('Y') }} UW Information School</a>,
            <a href="http://www.seattle.gov/">Seattle, Washington</a>
        </div>
    </div>
    <div class="modal right fade" id="_modal" tabindex="-1" role="dialog" style="display:none;">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div id="_modal_content"></div>
                <div id="_modal_content_panel" class="modal-content js-panel"></div>
            </div>
        </div>
    </div>
</div>

@if(config('app.env') !== 'production')
    <div id="env-warning-trigger" data-include="/budgets/env-warning.html"></div>
@endif

@yield('state')
<script type="text/javascript" src="/budgets/js/app.js{{ $cacheBusting }}"></script>
<script type="text/javascript" src="/budgets/js/context_menu_jquery.js{{ $cacheBusting }}"></script>
<script type="text/javascript" src="/budgets/js/env-warning.js{{ $cacheBusting }}"></script>
@yield('scripts')
</body>
</html>
