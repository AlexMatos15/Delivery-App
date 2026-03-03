@extends('adminlte::page')

@section('title', 'Editar Produto - ' . $product->name)

@section('adminlte_css')
    @php
        config([
            'adminlte.layout_topnav' => true,
            'adminlte.classes_body' => 'loja',
            'adminlte.menu' => [
                [
                    'text' => 'Painel',
                    'url' => 'loja/dashboard',
                    'icon' => 'fas fa-tachometer-alt',
                ],
                [
                    'text' => 'Pedidos',
                    'url' => 'loja/orders',
                    'icon' => 'fas fa-box',
                ],
                [
                    'text' => 'Produtos',
                    'url' => 'loja/products',
                    'icon' => 'fas fa-cube',
                ],
            ],
        ]);
    @endphp
@stop

@section('css')
    @include('loja.partials.styles')
@stop

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route('loja.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header bg-primary">
                        <h3 class="card-title">Editar Produto</h3>
                    </div>

                    <form action="{{ route('loja.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            <!-- Nome -->
                            <div class="form-group">
                                <label for="name">Nome do Produto *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Descrição -->
                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Categoria -->
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center">
                                    <label for="category_id" class="mb-0">Categoria *</label>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#createCategoryModal">
                                        <i class="fas fa-plus"></i> Nova Categoria
                                    </button>
                                </div>
                                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Preços -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price">Preço *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">R$</span>
                                            </div>
                                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" step="0.01" required>
                                        </div>
                                        @error('price')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="promotional_price">Preço Promocional</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">R$</span>
                                            </div>
                                            <input type="number" class="form-control @error('promotional_price') is-invalid @enderror" id="promotional_price" name="promotional_price" value="{{ old('promotional_price', $product->promotional_price) }}" step="0.01">
                                        </div>
                                        @error('promotional_price')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Estoque -->
                            <div class="form-group">
                                <label for="stock">Quantidade em Estoque *</label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                                @error('stock')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Imagem -->
                            <div class="form-group">
                                <label for="image">Imagem do Produto</label>
                                @if ($product->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 200px;">
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    <label class="custom-file-label" for="image">Escolher arquivo</label>
                                </div>
                                @error('image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Destaque -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="featured" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="featured">
                                        Destaque (exibir na página inicial)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Atualizar Produto
                            </button>
                            <a href="{{ route('loja.products.index') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Criar Categoria -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" role="dialog" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('loja.categories.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCategoryModalLabel">Criar Nova Categoria</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="new_category_name">Nome da Categoria *</label>
                            <input type="text" class="form-control" id="new_category_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="new_category_description">Descrição</label>
                            <textarea class="form-control" id="new_category_description" name="description" rows="3"></textarea>
                        </div>
                        <small class="text-muted">A categoria criada ficará ativa imediatamente.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Criar Categoria
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Atualizar label do arquivo
        document.getElementById('image').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Escolher arquivo';
            e.target.nextElementSibling.textContent = fileName;
        });
    </script>
@endsection
