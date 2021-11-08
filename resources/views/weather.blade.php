<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <!-- Favicon-->
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
{{--        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">--}}

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">


        <title>{{ config('app.name', 'Laravel') }}</title>
    </head>


    <body class="container">

        <div id="app">
            <weather
            ></weather>
        </div>
    </body>
</html>
