<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Tela Inicial</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Bem-vindo ao Sistema</h1>

    <div class="card-deck mt-5">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Autores</h5>
                <p class="card-text">Gerenciar Autores</p>
                <a href="{{ route('authors.index') }}" class="btn btn-primary">Ver Autores</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Assuntos</h5>
                <p class="card-text">Gerenciar Assuntos</p>
                <a href="{{ route('subjects.index') }}" class="btn btn-primary">Ver Assuntos</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Livros</h5>
                <p class="card-text">Gerenciar Livros</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">Ver Livros</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
