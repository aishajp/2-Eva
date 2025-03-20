@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Listado de Pictogramas</h1>
        
        <table class="table">
            <tbody>
                @php $contador = 0; @endphp
                @foreach($imagenes as $imagen)
                    @if($contador % 4 == 0)
                        <tr>
                    @endif
                    
                    <td>
                        <div><img src="{{ asset($imagen->imagen) }}" alt="{{ $imagen->descripcion }}"></div>
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
            </tbody>
        </table>
    </div>
</div>
@endsection