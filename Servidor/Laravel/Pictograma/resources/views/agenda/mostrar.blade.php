<!-- resources/views/agenda/mostrar.blade.php -->
@extends('layouts.app')

@section('title', 'Agenda del día ' . date('d/m/Y', strtotime($fecha)) . ' para ' . $persona->nombre)

@section('content')
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Pictograma</th>
                </tr>
            </thead>
            <tbody>
                @forelse($agenda as $item)
                    <tr>
                        <td>{{ date('H:i', strtotime($item->hora)) }}</td>
                        <td>
                            <span class="{{ $item->imagen }}" style="font-size: 24px;"></span>
                            {{ $item->imagen }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">No hay entradas en la agenda para esta fecha</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="form-group">
        <a href="{{ route('agenda.consultar') }}" class="btn btn-default">
            <span class="glyphicon glyphicon-arrow-left"></span> Volver
        </a>
    </div>
@endsection