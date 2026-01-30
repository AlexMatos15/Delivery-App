@extends('adminlte::page')

@section('title', 'Meus Endereços')

@section('content')
    <div class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Meus Endereços</h3>
                <div class="card-tools">
                    <a href="{{ route('addresses.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Adicionar Endereço
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($addresses->count() > 0)
                    <div class="row">
                        @foreach ($addresses as $address)
                            <div class="col-md-6 mb-3">
                                <div class="card {{ $address->is_default ? 'border-primary' : '' }}">
                                    <div class="card-header {{ $address->is_default ? 'bg-primary' : 'bg-light' }}">
                                        <h5 class="card-title {{ $address->is_default ? 'text-white' : '' }} mb-0">
                                            {{ $address->label }}
                                            @if ($address->is_default)
                                                <span class="badge badge-light float-right">
                                                    <i class="fas fa-star"></i> Padrão
                                                </span>
                                            @endif
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-sm mb-2">
                                            <strong>Rua:</strong> {{ $address->street }}, {{ $address->number }}
                                        </p>
                                        @if ($address->complement)
                                            <p class="text-sm mb-2"><strong>Complemento:</strong> {{ $address->complement }}</p>
                                        @endif
                                        <p class="text-sm mb-2"><strong>Bairro:</strong> {{ $address->neighborhood }}</p>
                                        <p class="text-sm mb-2"><strong>Cidade:</strong> {{ $address->city }} - {{ $address->state }}</p>
                                        <p class="text-sm mb-2"><strong>CEP:</strong> {{ $address->zip_code }}</p>
                                        @if ($address->reference)
                                            <p class="text-sm mb-2"><strong>Referência:</strong> {{ $address->reference }}</p>
                                        @endif
                                    </div>
                                    <div class="card-footer">
                                        @if (!$address->is_default)
                                            <form method="POST" action="{{ route('addresses.set-default', $address) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-xs btn-info">
                                                    <i class="fas fa-star"></i> Definir como Padrão
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('addresses.edit', $address) }}" class="btn btn-xs btn-primary">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>

                                        <form method="POST" action="{{ route('addresses.destroy', $address) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('Tem certeza que deseja deletar este endereço?')"
                                                    class="btn btn-xs btn-danger">
                                                <i class="fas fa-trash"></i> Deletar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-map-marker-alt fa-5x text-muted mb-3"></i>
                        <h4>Nenhum endereço cadastrado</h4>
                        <p class="text-muted">Adicione um endereço de entrega para fazer seu primeiro pedido.</p>
                        <a href="{{ route('addresses.create') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-plus"></i> Adicionar Endereço
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
