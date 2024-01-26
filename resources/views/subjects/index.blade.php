<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('head', ['title' => 'Editar Assuntos'])
<body>
<div class="container mt-5">
    <h1>Assuntos</h1>
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('welcome') }}" class="btn btn-secondary mt-3">Voltar</a>
        <a href="{{ route('subjects.create') }}" class="btn btn-primary mb-3">Adicionar</a>
    </div>
    <ul class="list-group">
        @forelse ($subjects['data'] as $subject)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $subject['description'] }}
                <div class="btn-group" role="group" aria-label="Author Actions">
                    <a href="{{ route('subjects.edit', $subject['id']) }}" class="btn btn-warning">Editar</a>

                    <button type="button" class="btn btn-danger" data-toggle="modal"
                            data-target="#deleteModal{{ $subject['id'] }}">
                        Excluir
                    </button>

                    <div class="modal fade" id="deleteModal{{ $subject['id'] }}" tabindex="-1" role="dialog"
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
                                    Tem certeza de que deseja excluir o autor "{{ $subject['description'] }}"?
                                </div>
                                <div class="modal-footer">
                                    <!-- Formulário para exclusão -->
                                    <form id="delete-form-{{ $subject['id'] }}"
                                          action="{{ route('subjects.destroy', $subject['id']) }}" method="POST">
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
            <li class="list-group-item">Nenhum assunto encontrado.</li>
        @endforelse
    </ul>

    <nav>
        <ul class="pagination">
            <li class="page-item {{ $subjects['current_page'] == 1 ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $subjects['first_page_url'] }}">Primeira</a>
            </li>
            @for ($i = 1; $i <= $subjects['last_page']; $i++)
                <li class="page-item {{ $i == $subjects['current_page'] ? 'active' : '' }}">
                    <a class="page-link" href="{{ $subjects['path'] }}?page={{ $i }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ $subjects['current_page'] == $subjects['last_page'] ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $subjects['last_page_url'] }}">Última</a>
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
