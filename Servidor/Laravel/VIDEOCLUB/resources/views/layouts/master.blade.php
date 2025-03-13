<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videoclub</title>
    <link href="{{ url('/assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body>
    @include('partials.navbar')
    
    <div class="container">
        @yield('content')
    </div>
    
    <script src="{{ url('/assets/bootstrap/js/jquery.min.js') }}"></script>
    <script src="{{ url('/assets/bootstrap/js/bootstrap.min.js') }}"></script>
</body>
</html>