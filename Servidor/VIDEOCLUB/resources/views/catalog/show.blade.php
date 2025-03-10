@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-sm-4">
        <img src="{{ $pelicula['poster'] }}" style="height:400px" />
    </div>
    <div class="col-sm-8">
        <h2>{{ $pelicula['title'] }}</h2>
        <h4>Año: {{ $pelicula['year'] }}</h4>
        <h4>Director: {{ $pelicula['director'] }}</h4>
        <p><strong>Resumen:</strong> {{ $pelicula['synopsis'] }}</p>
        <p><strong>Estado:</strong> 
            @if($pelicula['rented'])
                Película actualmente alquilada
            @else
                Película disponible
            @endif
        </p>
        
        @if($pelicula['rented'])
            <a href="#" class="btn btn-danger">Devolver película</a>
        @else
            <a href="#" class="btn btn-primary">Alquilar película</a>
        @endif
        
        <a href="{{ url('/catalog/edit/' . $id) }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-pencil"></span> Editar película
        </a>
        
        <a href="{{ url('/catalog') }}" class="btn btn-default">
            <span class="glyphicon glyphicon-chevron-left"></span> Volver al listado
        </a>
    </div>
</div>
@endsection