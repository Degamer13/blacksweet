@extends('layouts.admin')
@section('content')
    <div class="container">

    <h3>Detalle de Ejemplar</h3>


          <p class="card-text"><strong>Nombre:</strong> {{ $ejemplar->name }}</p>


    <a href="{{ route('ejemplars.index') }}" class="btn btn-secondary">Regresar</a>
</div>
@endsection
