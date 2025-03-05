@extends('layouts.admin')
@section('content')
    <div class="container">

    <h3>Detalle de Carrera</h3>


          <p class="card-text"><strong>Nombre:</strong> {{ $race->name }}</p>


    <a href="{{ route('races.index') }}" class="btn btn-secondary">Regresar</a>
</div>
@endsection
