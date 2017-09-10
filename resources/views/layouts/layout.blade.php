<html>
    <head>
        <title>Cafe Nokios - @yield('title')</title>
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    </head>
    <body>
        <div class="container">
            <div class="title">
                <h1>Cafe Nokios</h1>
            </div>
        </div>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>