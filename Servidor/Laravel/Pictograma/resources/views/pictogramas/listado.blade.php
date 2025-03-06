<!-- resources/views/pictogramas/listado.blade.php -->
@extends('layouts.app')

@section('title', 'Listado de Pictogramas')

@section('content')
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Ruta</th>
                </tr>
            </thead>
            <tbody>
                @forelse($imagenes as $imagen)
                    <tr>
                        <td>{{ $imagen->idimagen }}</td>
                        <td>
                            <span class="{{ $imagen->imagen }}" style="font-size: 24px;"></span>
                        </td>
                        <td>{{ $imagen->imagen }}</td>
                        <td>{{ $imagen->ruta ?? 'Sin ruta asignada' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No hay pictogramas registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection