<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NFT') }}</title>

    {{-- Scripts --}}
    <script src="{{ mix('js/app.js') }}" defer></script>

    {{-- Fonts --}}
    <script src="https://kit.fontawesome.com/f8f2a9650a.js" crossorigin="anonymous"></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
    <div id="app">
        @yield('extra_css')
        <div id="main-app">
            @yield('content')
        </div>
        @yield('extra_scripts')
    </div>
</body>

</html>