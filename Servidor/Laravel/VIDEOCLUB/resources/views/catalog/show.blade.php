@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-sm-4">
        <img src="{{$pelicula['poster']}}" style="height:400px"/>
    </div>
    <div class="col-sm-8">
        <h2>{{$pelicula['title']}}</h2>
        <h4>Año: {{$pelicula['year']}}</h4>
        <h4>Director: {{$pelicula['director']}}</h4>
        <p><strong>Resumen:</strong> {{$pelicula['synopsis']}}</p>
        
        <p><strong>Estado:</strong> 
            @if($pelicula['rented'])
                Película actualmente alquilada
            @else
                Película disponible
            @endif
        </p>
        
        <div class="mb-3">
            @if($pelicula['rented'])
                <a class="btn btn-danger" href="#">Devolver película</a>
            @else
                <a class="btn btn-primary" href="#">Alquilar película</a>
            @endif
            
            <a class="btn btn-warning" href="{{ url('/catalog/edit/' . $id) }}">Editar película</a>
            <a class="btn btn-light" href="{{ url('/catalog') }}">Volver al listado</a>
        </div>
    </div>
</div>
@endsection