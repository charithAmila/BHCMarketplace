<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Scripts --}}
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    {{-- Fonts --}}
    <script src="https://kit.fontawesome.com/f8f2a9650a.js" crossorigin="anonymous"></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


    {{-- Styles --}}
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/navbar.css') }}" rel="stylesheet">
    <link href="{{ mix('css/global.css') }}" rel="stylesheet">
    <link href="{{ mix('css/main.css') }}" rel="stylesheet">
    <link href="{{ mix('css/responsiveness.css') }}" rel="stylesheet">

    {{-- Scripts Vue and axios --}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>

</head>
<body>
    @yield('extra_css')
    <div id="main-app">
        @yield('content')
    </div>
    @yield('extra_scripts')
    <script>
        $(document).ready(function(){
        });
    </script>
</body>
</html>
