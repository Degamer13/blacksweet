@extends('layouts.admin')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Americas</h5>
                    <iframe id="iframeAmericas" src="" class="w-100" height="200"></iframe>
                    <button onclick="abrirEnNuevaPestana('iframeAmericas', 'http://www.macacohipico.com.ve/p/revistas-americanas.html')" class="btn btn-primary mt-2">Abrir en nueva pestaña</button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Nacionales</h5>
                    <iframe id="iframeNacionales" src="" class="w-100" height="200"></iframe>
                    <button onclick="abrirEnNuevaPestana('iframeNacionales', 'http://www.macacohipico.com.ve/p/revista-la-rinconada_30.html')" class="btn btn-primary mt-2">Abrir en nueva pestaña</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function abrirEnNuevaPestana(iframeId, url) {
        document.getElementById(iframeId).src = url; // Carga la URL en el iframe
        window.open(url, '_blank'); // Abre en nueva pestaña
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

@endsection
