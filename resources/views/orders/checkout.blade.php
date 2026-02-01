@extends('adminlte::page')

@section('title', 'Finalizar Compra')

@section('adminlte_css')
    @php
        config(['adminlte.layout_topnav' => true]);
    @endphp
@stop

@section('content')
    <div class="container-fluid">
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <div class="row">
                <!-- Left Column: Order Details -->
                <div class="col-lg-8">
                    <!-- Delivery Address -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title d-flex justify-content-between align-items-center">
                                <span>Endereço de Entrega</span>
                                <a href="{{ route('addresses.create') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-plus"></i> Novo Endereço
                                </a>
                            </h3>
                        </div>
                        <div class="card-body">
                            @if ($addresses->count() > 0)
                                <div class="address-list">
                                    @foreach ($addresses as $address)
                                        <label class="custom-control custom-radio mb-3">
                                            <input type="radio" 
                                                   class="custom-control-input" 
                                                   name="address_id" 
                                                   value="{{ $address->id }}" 
                                                   {{ $address->is_default ? 'checked' : '' }}
                                                   required>
                                            <div class="custom-control-label w-100 p-3 border rounded {{ $address->is_default ? 'border-primary bg-light' : '' }}">
                                                <div class="font-weight-bold">{{ $address->label }}</div>
                                                <div class="text-muted">{{ $address->street }}, {{ $address->number }}</div>
                                                @if($address->complement)
                                                    <div class="text-muted text-sm">{{ $address->complement }}</div>
                                                @endif
                                                <div class="text-muted">{{ $address->neighborhood }} - {{ $address->city }}/{{ $address->state }}</div>
                                                <div class="text-muted">{{ $address->zip_code }}</div>
                                                @if ($address->is_default)
                                                    <span class="badge badge-primary mt-2">Padrão</span>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('address_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            @else
                                <div class="alert alert-warning">
                                    <p class="mb-0">Você precisa adicionar um endereço de entrega primeiro.</p>
                                    <a href="{{ route('addresses.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus"></i> Adicionar Endereço
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Método de Pagamento</h3>
                        </div>
                        <div class="card-body">
                            <div class="payment-methods">
                                <label class="custom-control custom-radio mb-3">
                                    <input type="radio" name="payment_method" value="credit_card" checked required class="custom-control-input">
                                    <div class="custom-control-label w-100 p-3 border rounded">
                                        <i class="fas fa-credit-card"></i> Cartão de Crédito
                                    </div>
                                </label>
                                
                                <label class="custom-control custom-radio mb-3">
                                    <input type="radio" name="payment_method" value="debit_card" required class="custom-control-input">
                                    <div class="custom-control-label w-100 p-3 border rounded">
                                        <i class="fas fa-credit-card"></i> Cartão de Débito
                                    </div>
                                </label>
                                
                                <label class="custom-control custom-radio mb-3">
                                    <input type="radio" name="payment_method" value="pix" required class="custom-control-input">
                                    <div class="custom-control-label w-100 p-3 border rounded">
                                        <i class="fas fa-mobile-alt"></i> PIX
                                    </div>
                                </label>
                                
                                <label class="custom-control custom-radio mb-3">
                                    <input type="radio" name="payment_method" value="cash" required class="custom-control-input">
                                    <div class="custom-control-label w-100 p-3 border rounded">
                                        <i class="fas fa-money-bill"></i> Dinheiro
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Observações do Pedido</h3>
                        </div>
                        <div class="card-body">
                            <textarea name="notes" 
                                      rows="3" 
                                      placeholder="Alguma instrução especial para seu pedido?"
                                      class="form-control">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="col-lg-4">
                    <div class="card sticky-top">
                        <div class="card-header">
                            <h3 class="card-title">Resumo do Pedido</h3>
                        </div>
                        <div class="card-body">
                            <!-- Cart Items -->
                            <div class="mb-3" style="max-height: 250px; overflow-y: auto;">
                                @foreach ($cart as $item)
                                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                        <div style="width: 50px; height: 50px; flex-shrink: 0;">
                                            @if ($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}" 
                                                     alt="{{ $item['name'] }}" 
                                                     class="w-100 h-100 object-cover rounded" style="object-fit: cover;">
                                            @else
                                                <div class="w-100 h-100 bg-light rounded"></div>
                                            @endif
                                        </div>
                                        <div class="ml-3 flex-grow-1">
                                            <div class="font-weight-bold small">{{ $item['name'] }}</div>
                                            <div class="text-muted text-sm">{{ $item['quantity'] }}x R$ {{ number_format($item['price'], 2, ',', '.') }}</div>
                                        </div>
                                        <div class="font-weight-bold text-right">
                                            R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Totals -->
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Taxa de Entrega:</span>
                                    <span>R$ {{ number_format($deliveryFee, 2, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-top pt-3 font-weight-bold text-lg">
                                    <span>Total:</span>
                                    <span class="text-success">R$ {{ number_format($total, 2, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-4">
                                @if ($addresses->count() > 0)
                                    <button type="submit" class="btn btn-success btn-block btn-lg">
                                        <i class="fas fa-check"></i> Finalizar Pedido
                                    </button>
                                @endif
                                <a href="{{ route('cart.index') }}" class="btn btn-secondary btn-block mt-2">
                                    <i class="fas fa-arrow-left"></i> Voltar ao Carrinho
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
