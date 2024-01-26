<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head', ['title' => 'Criar Autor'])
<body>
<div class="container mt-5">
    <h1>Criar Autor</h1>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="post" action="{{ route('authors.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nome do Autor:</label>
            <input type="text" class="form-control" id="name" name="name" required  maxlength="40">
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Criar</button>
        <a href="{{ back()->getTargetUrl() }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
</body>
</html>
