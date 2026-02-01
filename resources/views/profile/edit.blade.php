@extends('adminlte::page')

@section('title', 'Editar Perfil')

@section('adminlte_css')
    @php
        // Remove sidebar para cliente e loja
        if (auth()->check() && !auth()->user()->isAdmin()) {
            config(['adminlte.layout_topnav' => true]);
        }
    @endphp
@stop

@section('content')
    <div class="container-fluid">
        <!-- Atalhos Rápidos -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Atalhos Rápidos</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('addresses.index') }}" class="btn btn-primary mr-2">
                            <i class="fas fa-map-marker-alt"></i> Gerenciar Endereços
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-info">
                            <i class="fas fa-box"></i> Meus Pedidos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Atualizar Informações -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Informações do Perfil</h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Alterar Senha -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Alterar Senha</h3>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
