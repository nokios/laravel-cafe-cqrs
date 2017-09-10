<html>
    <head>
        <title>Cafe Nokios - @yield('title')</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>
    <body>
        <div class="container border border-top-0 border-left-0 border-right-0">
            <div class="title">
                <h1>Cafe Nokios</h1>
            </div>
        </div>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>