@extends('adminlte::page')

@section('title', 'Adicionar Endereço')

@section('adminlte_css')
    @php
        config(['adminlte.layout_topnav' => true]);
    @endphp
@stop

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Adicionar Novo Endereço</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('addresses.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="label">Rótulo (ex: Casa, Trabalho)</label>
                                <input id="label" name="label" type="text" class="form-control @error('label') is-invalid @enderror" value="{{ old('label') }}" required autofocus>
                                @error('label')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="zip_code">CEP</label>
                                    <input id="zip_code" name="zip_code" type="text" class="form-control @error('zip_code') is-invalid @enderror" value="{{ old('zip_code') }}" required placeholder="00000-000">
                                    @error('zip_code')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="state">Estado</label>
                                    <input id="state" name="state" type="text" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}" required maxlength="2" placeholder="SP">
                                    @error('state')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="city">Cidade</label>
                                <input id="city" name="city" type="text" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>
                                @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="neighborhood">Bairro</label>
                                <input id="neighborhood" name="neighborhood" type="text" class="form-control @error('neighborhood') is-invalid @enderror" value="{{ old('neighborhood') }}" required>
                                @error('neighborhood')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-9">
                                    <label for="street">Rua</label>
                                    <input id="street" name="street" type="text" class="form-control @error('street') is-invalid @enderror" value="{{ old('street') }}" required>
                                    @error('street')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="number">Número</label>
                                    <input id="number" name="number" type="text" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}" required>
                                    @error('number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="complement">Complemento (opcional)</label>
                                <input id="complement" name="complement" type="text" class="form-control @error('complement') is-invalid @enderror" value="{{ old('complement') }}" placeholder="Apt, Sala, Andar, etc.">
                                @error('complement')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="reference">Ponto de Referência (opcional)</label>
                                <textarea id="reference" name="reference" rows="2" class="form-control @error('reference') is-invalid @enderror" placeholder="Perto da padaria, prédio azul, etc.">{{ old('reference') }}</textarea>
                                @error('reference')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                    <span>Definir como endereço padrão</span>
                                </label>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Salvar Endereço
                                </button>
                                <a href="{{ route('addresses.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
