@extends('layouts.admin')

@section('content')
<div class="container">
<div class="row">
    <div class="col-lg-9">
        <div class="pull-left">
            <h3>Usuarios</h3>
        </div>

    </div>
</div>
    @can('user-create')
        
  
     <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Agregar Usuario</a>
     @endcan
   @can('user-search')
       

     <div class="col-12 col-md-6 mb-3">

        <form class="">

            <div class="input-group" >
        <input type="text" class="form-control"   name="buscarpor" value="{{$buscarpor}}"   type="search" placeholder="Buscar" aria-label="Search" aria-describedby="button-addon2">
        <div class="input-group-append">
          <button class="btn btn-success"  id="button-addon2" type="submit"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
          </svg></button>
        </div>
      </div>
        </form>
    </div>
    @endcan


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
<table class="table table-bordered">
    <tr>

        <th>Nombre</th>
        <th>Correo Electronico</th>
        <th>Roles</th>

        <th width="280px">Acciones</th>
    </tr>
@foreach ($data as $key => $user)
    <tr>

        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>
            @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $v)
                    <span class="badge rounded-pill bg-dark">{{ $v }}</span>
                @endforeach
            @endif
        </td>

        <td class="text-center">
            @can('user-show')
                
         
            <a class="btn btn-info" href="{{ route('users.show',$user->id) }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
              </svg></a>
              @endcan
          @can('user-edit')


            <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
              </svg></a>
            @endcan
            @can('user-delete')
            <form method="POST" action="{{ route('users.destroy', $user->id) }}" style="display:inline" id="delete-form-{{ $user->id }}">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $user->id }})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                    </svg>
                </button>
            </form> @endcan
        </td>
    </tr>
@endforeach
</table>
</div>


{!! $data->render() !!}
<!-- Agrega esto en tu archivo blade -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
            }
        });
    }
</script>
@endsection
