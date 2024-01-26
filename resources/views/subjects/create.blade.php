<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head', ['title' => 'Criar Assunto'])
<body>
<div class="container mt-5">
    <h1>Criar Assunto</h1>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="post" action="{{ route('subjects.store') }}">
        @csrf
        <div class="form-group">
            <label for="description">Descrição do Assunto:</label>
            <input type="text" class="form-control" id="description" name="description" required  maxlength="20">
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
