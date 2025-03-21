<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: white;
            color: black;
        }
        .navbar {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
        }
        .content-card {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease-in-out;
        }
        .content-card:hover {
            transform: scale(1.05);
        }
        .card-1 { background: #007bff; color: white; }
        .card-2 { background: #28a745; color: white; }
        .card-3 { background: #dc3545; color: white; }
        .icon {
            font-size: 40px;
            margin-bottom: 10px;
            color: gold;
        }
        .full-width-image {
            width: 100%;
            height: auto;
            display: block;
        }
        footer {
            background-color: #000;
            color: white;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/home') }}" class="nav-link">{{ __('Home') }}</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">{{ __('Login') }}</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-0">
        <img src="{{ asset('img/CABALLOS.jpg') }}" class="full-width-image" alt="Imagen de caballos">
    </div>

    <div class="container mt-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="content-card card-1">
                    <i class="fas fa-horse-head icon"></i>
                    <h4>Apuestas Intensas</h4>
                    <p>Emoción y adrenalina en cada carrera, vive la pasión del turf.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-card card-2">
                    <i class="fas fa-money-bill-wave icon"></i>
                    <h4>Gana a lo Grande</h4>
                    <p>Las mejores cuotas y oportunidades para hacerte con la victoria.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="content-card card-3">
                    <i class="fas fa-medal icon"></i>
                    <h4>Solo Campeones</h4>
                    <p>Los mejores caballos y jockeys compiten por la gloria.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h3 class="text-center">Próximas Carreras</h3>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Evento</th>
                    <th>Premio</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>25 de Marzo</td>
                    <td>Gran Derby Nacional</td>
                    <td>$50,000</td>
                </tr>
                <tr>
                    <td>2 de Abril</td>
                    <td>Copa de Velocidad</td>
                    <td>$30,000</td>
                </tr>
                <tr>
                    <td>10 de Abril</td>
                    <td>Clásico de Primavera</td>
                    <td>$40,000</td>
                </tr>
            </tbody>
        </table>
    </div>



    <footer class="text-center py-3 mt-5">
        &copy; 2025 Todos los derechos reservados | {{ config('app.name', 'Laravel') }}
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
