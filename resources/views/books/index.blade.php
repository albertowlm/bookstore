<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head', ['title' => 'Livros'])
<body>
<div class="container mt-5">

    <h1>Livros</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('welcome') }}" class="btn btn-secondary mt-3">Voltar</a>
        <a href="{{ route('books.create') }}" class="btn btn-primary mb-3">Adicionar Livro</a>
    </div>
    <table class="table">
        <thead>
        <tr>
            <th>Título</th>
            <th>Editora</th>
            <th>Edição</th>
            <th>Ano de Publicação</th>
            <th>Preço</th>
            <th>Autores</th>
            <th>Assuntos</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($books['data'] as $book)
            <tr>
                <td>{{ $book['title'] }}</td>
                <td>{{ $book['publishing_companies'] }}</td>
                <td>{{ $book['edition'] }}</td>
                <td>{{ $book['year_publication'] }}</td>
                <td style="white-space: nowrap;">R$ {{ str_replace('.',',',$book['price']) }}</td>
                <td>{{ $book['authors_name'] }}</td>
                <td>{{ $book['subjects_description'] }}</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('books.edit', $book['id']) }}" class="btn btn-warning">Editar</a>
                        <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#deleteModal{{ $book['id'] }}">
                            Excluir
                        </button>
                        <div class="modal fade" id="deleteModal{{ $book['id'] }}" tabindex="-1" role="dialog"
                             aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel">Confirmação de Exclusão</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Tem certeza de que deseja excluir o autor "{{ $book['title'] }}"?
                                    </div>
                                    <div class="modal-footer">
                                        <!-- Formulário para exclusão -->
                                        <form id="delete-form-{{ $book['id'] }}"
                                              action="{{ route('books.destroy', $book['id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Excluir</button>
                                        </form>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">Nenhum livro encontrado.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <nav>
        <ul class="pagination">
            <li class="page-item {{ $books['current_page'] == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $books['first_page_url'] }}">Primeira</a>
            </li>
            @for ($i = 1; $i <= $books['last_page']; $i++)
                <li class="page-item {{ $i == $books['current_page'] ? 'active' : '' }}">
                    <a class="page-link" href="{{ $books['path'] }}?page={{ $i }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ $books['current_page'] == $books['last_page'] ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $books['last_page_url'] }}">Última</a>
            </li>
        </ul>
    </nav>

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div>

@include('script')

</body>
</html>
