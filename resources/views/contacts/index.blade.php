@extends('layouts.app')

@section('title', 'Lista de Contatos')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="fas fa-address-book"></i> Contatos</h1>
            <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Novo Contato
            </a>
        </div>

        @if($contacts->count() > 0)
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Telefone</th>
                                    <th>Criado em</th>
                                    <th width="150">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                    <tr>
                                        <td>
                                            <strong>{{ $contact->name }}</strong>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $contact->email }}">
                                                {{ $contact->email }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="tel:{{ $contact->phone }}">
                                                {{ $contact->phone }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $contact->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('contacts.show', $contact->id) }}"
                                                   class="btn btn-outline-primary"
                                                   title="Visualizar">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('contacts.edit', $contact->id) }}"
                                                   class="btn btn-outline-warning"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('contacts.destroy', $contact->id) }}"
                                                      style="display: inline;"
                                                      onsubmit="return confirm('Tem certeza que deseja excluir este contato?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger"
                                                            title="Excluir">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Mostrando {{ $contacts->firstItem() }} a {{ $contacts->lastItem() }}
                            de {{ $contacts->total() }} contatos
                        </div>
                        <div>
                            {{ $contacts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-address-book fa-3x text-muted mb-3"></i>
                    <h4>Nenhum contato encontrado</h4>
                    <p class="text-muted">Comece criando seu primeiro contato.</p>
                    <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Criar Primeiro Contato
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
