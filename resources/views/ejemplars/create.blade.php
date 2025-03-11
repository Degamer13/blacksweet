@extends('layouts.admin')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<h3>Registrar Carrera</h3>
<form action="{{ route('ejemplars.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" required>
        <span id="mensaje-validacion" style="font-weight: bold;"></span>
    </div>
    <button type="submit" class="btn btn-primary">Guardar</button>
    <a href="{{ route('ejemplars.index') }}" class="btn btn-secondary">Cancelar</a>
</form>

@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#name').on('input', function () {
            let name = $(this).val().trim();
            if (name.length > 0) {
                $.ajax({
                    url: `/validar-ejemplar/nombre/${encodeURIComponent(name)}`,
                    type: 'GET',
                    success: function (response) {
                        $('#mensaje-validacion').text(response.message).css('color', 'green');
                    },
                    error: function (xhr) {
                        $('#mensaje-validacion').text(xhr.responseJSON.message).css('color', 'red');
                    }
                });
            } else {
                $('#mensaje-validacion').text('');
            }
        });
    });
</script>

