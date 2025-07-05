@extends('layouts.app')

@section('title', 'Visualizar Contato')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-eye"></i> Visualizar Contato</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Nome</h5>
                        <p class="text-muted">{{ $contact->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>Email</h5>
                        <p class="text-muted">
                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Telefone</h5>
                        <p class="text-muted">
                            <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>Criado em</h5>
                        <p class="text-muted">{{ $contact->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('contacts.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <div>
                        <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form method="POST" 
                              action="{{ route('contacts.destroy', $contact->id) }}" 
                              style="display: inline;" 
                              onsubmit="return confirm('Tem certeza que deseja excluir este contato?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Excluir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
