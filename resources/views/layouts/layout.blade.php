<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Cafe Nokios - @yield('title')</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>
    <body>
        <div class="container border border-top-0 border-left-0 border-right-0">
            <div class="title">
                <h1>Cafe Nokios</h1>
            </div>
        </div>
        <div class="container" id="app">
            @yield('content')
        </div>
        <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
    </body>
</html>