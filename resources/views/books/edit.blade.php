<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head', ['title' => 'Editar Livro'])
<body>
<div class="container mt-5">
    <h1>Editar Livro</h1>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="post" action="{{ route('books.update', $book['id']) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Título:</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $book['title'] }}" required maxlength="40">
        </div>

        <div class="form-group">
            <label for="publishing_companies">Editora:</label>
            <input type="text" class="form-control" id="publishing_companies" name="publishing_companies" value="{{ $book['publishing_companies'] }}" required maxlength="40">
        </div>

        <div class="form-group">
            <label for="edition">Edição:</label>
            <input type="number" class="form-control" id="edition" name="edition" value="{{ $book['edition'] }}" required>
        </div>

        <div class="form-group">
            <label for="year_publication">Ano de Publicação:</label>
            <input type="text" class="form-control" id="year_publication" name="year_publication" value="{{ $book['year_publication'] }}" required maxlength="4" pattern="[0-9]{4}" title="Por favor, insira um ano válido (4 dígitos)">
        </div>

        <div class="form-group">
            <label for="price">Preço:</label>
            <input type="text" class="form-control price-input" id="price" name="price" value="{{ $book['price'] }}" data-thousands="." data-decimal="," data-prefix="R$ " maxlength="29" required>
        </div>

        <div class="form-group">
            <label for="authors">Autores:</label>
            <select class="form-control" id="authors" name="author_ids[]" multiple required>
                @foreach($authors as $author)
                    <option value="{{ $author['id'] }}" {{ in_array($author['id'], $book['authors_ids']) ? 'selected' : '' }}>{{ $author['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="subjects">Assuntos:</label>
            <select class="form-control" id="subjects" name="subject_ids[]" multiple required>
                @foreach($subjects as $subject)
                    <option value="{{ $subject['id'] }}" {{ in_array($subject['id'], $book['subjects_ids']) ? 'selected' : '' }}>{{ $subject['description'] }}</option>
                @endforeach
            </select>
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

        <button type="submit" class="btn btn-primary">Atualizar</button>
        <a href="{{ back()->getTargetUrl() }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
</body>

<script>
    $("#price").maskMoney();
    $('form').submit(function() {
        var priceValue = $('#price').maskMoney('unmasked')[0];
        $('#price').val(priceValue);
    });
    $(document).ready(function() {
        $('#authors').select2({
            placeholder: 'Selecione os autores',
            allowClear: true,
            closeOnSelect: false
        });
    });
    $(document).ready(function() {
        $('#subjects').select2({
            placeholder: 'Selecione os assuntos',
            allowClear: true,
            closeOnSelect: false
        });
    });
    $(document).ready(function() {
        $('#year_publication').on('input', function() {
            var sanitizedValue = $(this).val().replace(/[^0-9]/g, '');
            sanitizedValue = sanitizedValue.slice(0, 4);
            $(this).val(sanitizedValue);
        });
    });
</script>

</html>
