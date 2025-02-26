@extends('layouts.master')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Modificar película</h1>
        <form action="{{ url('/catalog/edit/' . $id) }}" method="POST">
            @csrf
            {{ method_field('PUT') }}
            
            <div class="form-group">
                <label for="title">Título</label>
                <input type="text" name="title" id="title" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="year">Año</label>
                <input type="text" name="year" id="year" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="director">Director</label>
                <input type="text" name="director" id="director" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="poster">Poster</label>
                <input type="text" name="poster" id="poster" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="synopsis">Resumen</label>
                <textarea name="synopsis" id="synopsis" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">Modificar película</button>
            </div>
        </form>
    </div>
</div>
@endsection