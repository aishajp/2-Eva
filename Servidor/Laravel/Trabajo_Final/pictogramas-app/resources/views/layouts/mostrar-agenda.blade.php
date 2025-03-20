<!-- resources/views/pictogramas/mostrar-agenda.blade.php -->
@extends('layouts.app')

@section('title', 'Ver Agenda')

@section('content')
    <div class="row">
        <div class="col-12">
            <h1>Ver Agenda</h1>
        </div>
    </div>

    <form action="{{ route('agenda.mostrar.post') }}" method="POST">
        @csrf
        <div class="mb-3 col-4">
            <label for="fecha" class="form-label">Fecha</label>
            <input type="date" class="form-control" name="fecha" id="fecha" value="{{ $fechaHoy }}">
        </div>
        
        <div class="mb-3 col-4">
            <label for="persona" class="form-label">Persona</label>
            <select class="form-select form-select-lg" name="persona" id="persona">
                @foreach($personas as $persona)
                    <option value="{{ $persona->idpersona }}" {{ isset($personaSeleccionada) && $personaSeleccionada == $persona->idpersona ? 'selected' : '' }}>
                        {{ $persona->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <button type="submit" class="btn btn-info mt-3">Mostrar Agenda</button>
        <a href="{{ route('pictogramas.listado') }}" class="btn btn-secondary mt-3">Volver al Listado</a>
    </form>
    
    @if(isset($agenda))
        <div class="row mt-4">
            <div class="col-12">
                <h3>Agenda para la fecha seleccionada</h3>
                @if(count($agenda) > 0)
                    <table class="table-responsive">
                        <tbody>
                            @php $contador = 0; @endphp
                            @foreach($agenda as $item)
                                @if($contador == 0)
                                    <tr>
                                @endif
                                
                                <td>
                                    <div><img src="{{ asset($item->imagen) }}" alt="Imagen de actividad"></div>
                                    <div>{{ $item->imagen }}</div>
                                    <div>Hora: {{ $item->hora }}</div>
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
                @else
                    <div class="alert alert-info">
                        No hay actividades agendadas para esta fecha y persona.
                    </div>
                @endif
            </div>
        </div>
    @endif
@endsection