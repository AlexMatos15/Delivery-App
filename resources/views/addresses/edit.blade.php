@extends('layouts.client')

@section('title', 'Editar Endereço')

@section('content')
    <div class="client-container">
        <div class="client-section">
            <h2 class="client-section-title">✏️ Editar Endereço</h2>

            <form method="POST" action="{{ route('addresses.update', $address) }}" class="client-form">
                @csrf
                @method('PUT')

                <div class="client-form-group">
                    <label for="label" class="client-form-label">Rótulo (ex: Casa, Trabalho) *</label>
                    <input id="label" name="label" type="text" 
                           class="client-form-input @error('label') error @enderror" 
                           value="{{ old('label', $address->label) }}" 
                           required autofocus
                           placeholder="Casa, Trabalho, Academia...">
                    @error('label')
                        <span class="client-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="client-form-group">
                        <label for="zip_code" class="client-form-label">CEP *</label>
                        <input id="zip_code" name="zip_code" type="text" 
                               class="client-form-input @error('zip_code') error @enderror" 
                               value="{{ old('zip_code', $address->zip_code) }}" 
                               required 
                               placeholder="00000-000">
                        @error('zip_code')
                            <span class="client-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="client-form-group">
                        <label for="state" class="client-form-label">Estado *</label>
                        <input id="state" name="state" type="text" 
                               class="client-form-input @error('state') error @enderror" 
                               value="{{ old('state', $address->state) }}" 
                               required 
                               maxlength="2" 
                               placeholder="SP">
                        @error('state')
                            <span class="client-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="client-form-group">
                    <label for="city" class="client-form-label">Cidade *</label>
                    <input id="city" name="city" type="text" 
                           class="client-form-input @error('city') error @enderror" 
                           value="{{ old('city', $address->city) }}" 
                           required
                           placeholder="São Paulo">
                    @error('city')
                        <span class="client-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="client-form-group">
                    <label for="neighborhood" class="client-form-label">Bairro *</label>
                    <input id="neighborhood" name="neighborhood" type="text" 
                           class="client-form-input @error('neighborhood') error @enderror" 
                           value="{{ old('neighborhood', $address->neighborhood) }}" 
                           required
                           placeholder="Centro">
                    @error('neighborhood')
                        <span class="client-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 3fr 1fr; gap: 16px;">
                    <div class="client-form-group">
                        <label for="street" class="client-form-label">Rua *</label>
                        <input id="street" name="street" type="text" 
                               class="client-form-input @error('street') error @enderror" 
                               value="{{ old('street', $address->street) }}" 
                               required
                               placeholder="Rua das Flores">
                        @error('street')
                            <span class="client-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="client-form-group">
                        <label for="number" class="client-form-label">Número *</label>
                        <input id="number" name="number" type="text" 
                               class="client-form-input @error('number') error @enderror" 
                               value="{{ old('number', $address->number) }}" 
                               required
                               placeholder="123">
                        @error('number')
                            <span class="client-form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="client-form-group">
                    <label for="complement" class="client-form-label">Complemento</label>
                    <input id="complement" name="complement" type="text" 
                           class="client-form-input @error('complement') error @enderror" 
                           value="{{ old('complement', $address->complement) }}" 
                           placeholder="Apto 45, Bloco B, etc.">
                    @error('complement')
                        <span class="client-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="client-form-group">
                    <label for="reference" class="client-form-label">Ponto de Referência</label>
                    <textarea id="reference" name="reference" rows="2" 
                              class="client-form-input @error('reference') error @enderror" 
                              placeholder="Perto da padaria, prédio azul, etc.">{{ old('reference', $address->reference) }}</textarea>
                    @error('reference')
                        <span class="client-form-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="client-form-group">
                    <label class="client-checkbox-label">
                        <input type="checkbox" name="is_default" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
                        <span>⭐ Definir como endereço padrão</span>
                    </label>
                </div>

                <div style="display: flex; gap: 12px; margin-top: 24px;">
                    <button type="submit" class="client-btn primary client-btn-lg">
                        💾 Atualizar Endereço
                    </button>
                    <a href="{{ route('addresses.index') }}" class="client-btn secondary client-btn-lg">
                        ❌ Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
