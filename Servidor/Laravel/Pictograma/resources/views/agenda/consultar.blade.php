<!-- resources/views/agenda/consultar.blade.php -->
@extends('layouts.app')

@section('title', 'Consultar Agenda')

@section('content')
    <form method="POST" action="{{ route('agenda.mostrar') }}">
        @csrf
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="persona">Persona:</label>
                    <select class="form-control" id="persona" name="persona" required>
                        <option value="">Seleccionar persona</option>
                        @foreach($personas as $persona)
                            <option value="{{ $persona->idpersona }}" {{ old('persona') == $persona->idpersona ? 'selected' : '' }}>
                                {{ $persona->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-search"></span> Consultar
            </button>
        </div>
    </form>
@endsection