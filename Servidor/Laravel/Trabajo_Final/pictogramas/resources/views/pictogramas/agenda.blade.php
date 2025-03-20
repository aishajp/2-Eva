@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Ver Agenda</h1>
    </div>
</div>

<form action="{{ route('pictogramas.filterAgenda') }}" method="POST">
    @csrf
    <div class="mb-3 col-4">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="date" class="form-control" name="fecha" id="fecha" value="{{ $fechaHoy }}">
    </div>
    <div class="mb-3 col-4">
        <label for="persona" class="form-label">Persona</label>
        <select class="form-select form-select-lg" name="persona" id="persona">
            @foreach($personas as $persona)
                <option value="{{ $persona->idpersona }}">{{ $persona->nombre }}</option>
            @endforeach
        </select>
    </div>
    
    <button type="submit" class="btn btn-info mt-3" name="enviar">Mostrar Agenda</button>
    <a href="{{ route('pictogramas.index') }}" class="btn btn-secondary mt-3">Volver al Listado</a>
</form>

<div class="row mt-4">
    @if(isset($agenda))
        <table>
            @php $contador = 0; @endphp
            @foreach($agenda as $item)
                @if($contador % 4 == 0)
                    <tr>
                @endif
                
                <td>
                    <div><img src="{{ asset($item->imagen) }}" alt="Imagen"></div>
                    <div>{{ $item->imagen }}</div>
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
    @endif
</div>
@endsection