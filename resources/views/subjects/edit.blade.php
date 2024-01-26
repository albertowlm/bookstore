<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head', ['title' => 'Editar Assunto'])
<body>
<div class="container mt-5">
    <h1>Editar Assunto</h1>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="post" action="{{ route('subjects.update', $subject['id']) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="description">Descrição do Assunto:</label>
            <input type="text" class="form-control" id="description" name="description" value="{{ $subject['description'] }}" required  maxlength="20">
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

        <button type="submit" class="btn btn-primary">Alterar</button>
        <a href="{{ back()->getTargetUrl() }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
