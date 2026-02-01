@extends('adminlte::page')

@section('title', 'Editar Produto')

@section('content_header')
    <h1>Editar Produto: {{ $product->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informações do Produto</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nome <span class="text-danger">*</span></label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name', $product->name) }}" 
                           required 
                           autofocus>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="category_id">Categoria <span class="text-danger">*</span></label>
                    <select id="category_id" 
                            name="category_id" 
                            class="form-control @error('category_id') is-invalid @enderror" 
                            required>
                        <option value="">Selecione uma categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Descrição</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3" 
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price">Preço (R$) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   step="0.01" 
                                   min="0" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   value="{{ old('price', $product->price) }}" 
                                   required>
                            @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="promotional_price">Preço Promocional (R$)</label>
                            <input type="number" 
                                   id="promotional_price" 
                                   name="promotional_price" 
                                   step="0.01" 
                                   min="0" 
                                   class="form-control @error('promotional_price') is-invalid @enderror" 
                                   value="{{ old('promotional_price', $product->promotional_price) }}">
                            @error('promotional_price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Deve ser menor que o preço normal</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="stock">Estoque <span class="text-danger">*</span></label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           min="0" 
                           class="form-control @error('stock') is-invalid @enderror" 
                           value="{{ old('stock', $product->stock) }}" 
                           required>
                    @error('stock')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                @if ($product->image)
                    <div class="form-group">
                        <label>Imagem Atual</label>
                        <div>
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-thumbnail" 
                                 style="max-width: 200px;">
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label for="image">{{ $product->image ? 'Alterar Imagem' : 'Imagem do Produto' }}</label>
                    <div class="custom-file">
                        <input type="file" 
                               id="image" 
                               name="image" 
                               class="custom-file-input @error('image') is-invalid @enderror" 
                               accept="image/*">
                        <label class="custom-file-label" for="image">Escolher arquivo</label>
                    </div>
                    @error('image')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Tamanho máximo: 2MB. Formatos aceitos: JPG, PNG, GIF</small>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="is_active" 
                               name="is_active" 
                               value="1" 
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">
                            Produto Ativo
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" 
                               class="custom-control-input" 
                               id="is_featured" 
                               name="is_featured" 
                               value="1" 
                               {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_featured">
                            Produto em Destaque
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Atualizar Produto
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
    // Update custom file input label with selected filename
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
</script>
@stop

