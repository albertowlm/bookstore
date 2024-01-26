<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head', ['title' => 'Autores'])
<body>
<div class="container mt-5">
    <h1>Autores</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('welcome') }}" class="btn btn-secondary mt-3">Voltar</a>
        <a href="{{ route('authors.create') }}" class="btn btn-primary mb-3">Adicionar</a>
    </div>
    <ul class="list-group">
        @forelse ($authors['data'] as $author)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $author['name'] }}
                <div class="btn-group" role="group" aria-label="Author Actions">
                    <a href="{{ route('authors.edit', $author['id']) }}" class="btn btn-warning">Editar</a>

                    <button type="button" class="btn btn-danger" data-toggle="modal"
                            data-target="#deleteModal{{ $author['id'] }}">
                        Excluir
                    </button>

                    <div class="modal fade" id="deleteModal{{ $author['id'] }}" tabindex="-1" role="dialog"
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
                                    Tem certeza de que deseja excluir o autor "{{ $author['name'] }}"?
                                </div>
                                <div class="modal-footer">
                                    <form id="delete-form-{{ $author['id'] }}"
                                          action="{{ route('authors.destroy', $author['id']) }}" method="POST">
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
            </li>
        @empty
            <li class="list-group-item">Nenhum livro encontrado.</li>
        @endforelse
    </ul>

    <nav>
        <ul class="pagination">
            <li class="page-item {{ $authors['current_page'] == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $authors['first_page_url'] }}">Primeira</a>
            </li>

            @for ($i = 1; $i <= $authors['last_page']; $i++)
                <li class="page-item {{ $i == $authors['current_page'] ? 'active' : '' }}">
                    <a class="page-link" href="{{ $authors['path'] }}?page={{ $i }}">{{ $i }}</a>
                </li>
            @endfor

            <li class="page-item {{ $authors['current_page'] == $authors['last_page'] ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $authors['last_page_url'] }}">Última</a>
            </li>
        </ul>
    </nav>
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
</div>

@include('script')

</body>
</html>
