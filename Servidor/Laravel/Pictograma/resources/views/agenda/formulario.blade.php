<!-- resources/views/agenda/formulario.blade.php -->
@extends('layouts.app')

@section('title', 'Nueva Entrada de Agenda')

@section('content')
    <form method="POST" action="{{ route('agenda.guardar') }}">
        @csrf
        
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}" required>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label for="hora">Hora:</label>
                    <input type="time" class="form-control" id="hora" name="hora" value="{{ old('hora', date('H:i')) }}" required>
                </div>
            </div>
            
            <div class="col-md-4">
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
        </div>
        
        <div class="form-group">
            <label>Seleccione un pictograma:</label>
            <div class="row">
                @foreach($imagenes as $imagen)
                    <div class="col-md-3 text-center">
                        <div class="radio-container">
                            <span class="{{ $imagen->imagen }}" style="font-size: 36px;"></span>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="imagen" value="{{ $imagen->idimagen }}" {{ old('imagen') == $imagen->idimagen ? 'checked' : '' }} required>
                                    {{ $imagen->imagen }}
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">
                <span class="glyphicon glyphicon-floppy-disk"></span> Guardar
            </button>
        </div>
    </form>
@endsection