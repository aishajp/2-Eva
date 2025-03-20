@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h3>Añadir datos agenda</h3>
    </div>
</div>

<form action="{{ route('pictogramas.store') }}" method="POST">
    @csrf
    <div class="mb-3 col-4">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="date" class="form-control" name="fecha" id="fecha" value="{{ $fechaHoy }}">
    </div>
    <div class="mb-3 col-4">
        <label for="hora" class="form-label">Hora</label>
        <input type="time" class="form-control" name="hora" id="hora" value="{{ $hora }}">
    </div>
    <div class="mb-3 col-4">
        <label for="persona" class="form-label">Persona</label>
        <select class="form-select form-select-lg" name="persona" id="persona">
            @foreach($personas as $persona)
                <option value="{{ $persona->idpersona }}">{{ $persona->nombre }}</option>
            @endforeach
        </select>
    </div>
    
    <table>
        @php $contador = 0; @endphp
        @foreach($imagenes as $imagen)
            @if($contador % 4 == 0)
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
            @if($contador % 4 == 0)
                </tr>
            @endif
        @endforeach
        
        @if($contador % 4 != 0)
            </tr>
        @endif
    </table>
    
    <button type="submit" class="btn btn-info mt-3">Añadir entrada en agenda</button>
    <a href="{{ route('pictogramas.index') }}" class="btn btn-secondary mt-3">Volver al Listado</a>
</form>
@endsection