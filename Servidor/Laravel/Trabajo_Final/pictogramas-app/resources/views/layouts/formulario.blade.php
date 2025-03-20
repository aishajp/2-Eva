<!-- resources/views/pictogramas/formulario.blade.php -->
@extends('layouts.app')

@section('title', 'Añadir a Agenda')

@section('content')
    <div class="row">
        <div class="col-12">
            <h3>Añadir datos agenda</h3>
            @if(session('mensaje'))
                <div class="alert alert-success">
                    {{ session('mensaje') }}
                </div>
            @endif
        </div>
    </div>

    <form action="{{ route('agenda.guardar') }}" method="POST">
        @csrf
        <div class="mb-3 col-4">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" class="form-control" name="fecha" id="fecha" value="{{ $fechaHoy }}">
        </div>
        
        <div class="mb-3 col-4">
            <label for="hora" class="form-label">Hora</label>
            <input type="time" class="form-control" name="hora" id="hora" value="{{ $horaActual }}">
        </div>
        
        <div class="mb-3 col-4">
            <label for="persona" class="form-label">Persona</label>
            <select class="form-select form-select-lg" name="persona" id="persona">
                @foreach($personas as $persona)
                    <option value="{{ $persona->idpersona }}">{{ $persona->nombre }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="row">
            <div class="col-12">
                <table class="table-responsive">
                    <tbody>
                        @php $contador = 0; @endphp
                        @foreach($imagenes as $imagen)
                            @if($contador == 0)
                                <tr>
                            @endif
                            
                            <td>
                                <div>
                                    <input type="radio" class="mostrarInput" name="actividad" value="{{ $imagen->idimagen }}" id="actividad_{{ $imagen->idimagen }}">
                                    <img src="{{ asset($imagen->imagen) }}" alt="{{ $imagen->descripcion }}">
                                    <p>Imagen: {{ $imagen->idimagen }}</p>
                                </div>
                                <div>{{ $imagen->imagen }}</div>
                            </td>
                            
                            @php $contador++; @endphp
                            
                            @if($contador == 4)
                                </tr>
                                @php $contador = 0; @endphp
                            @endif
                        @endforeach
                        
                        @if($contador > 0 && $contador < 4)
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <button type="submit" class="btn btn-info mt-3">Añadir entrada en agenda</button>
        <a href="{{ route('pictogramas.listado') }}" class="btn btn-secondary mt-3">Volver al Listado</a>
    </form>
@endsection