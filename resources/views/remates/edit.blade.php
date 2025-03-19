@extends(Auth::user()->hasRole('ventas') ? 'layouts.app1' : 'layouts.admin')

@section('content')


@endsection
