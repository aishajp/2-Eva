<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio Laravel - @yield('title')</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            margin-bottom: 50px;
        }
        .alert {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .img-thumbnail {
            width: 50px;
            height: 50px;
        }
        .radio-container {
            display: inline-block;
            margin-right: 15px;
            text-align: center;
        }
        .radio-container img {
            display: block;
            margin: 0 auto 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <a class="navbar-brand" href="/">Ejercicio Laravel</a>
                        </div>
                        <ul class="nav navbar-nav">
                            <li><a href="{{ route('pictogramas.listado') }}"><span class="glyphicon glyphicon-th"></span> Listado de Pictogramas</a></li>
                            <li><a href="{{ route('agenda.formulario') }}"><span class="glyphicon glyphicon-plus-sign"></span> Nueva Entrada de Agenda</a></li>
                            <li><a href="{{ route('agenda.consultar') }}"><span class="glyphicon glyphicon-list-alt"></span> Consultar Agenda</a></li>
                        </ul>
                    </div>
                </nav>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">@yield('title')</h3>
                    </div>
                    <div class="panel-body">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>