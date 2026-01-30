<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('orders.store') }}">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Order Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Delivery Address -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">{{ __('Delivery Address') }}</h3>
                                <a href="{{ route('addresses.create') }}" class="text-blue-600 hover:underline text-sm">
                                    + {{ __('Add New Address') }}
                                </a>
                            </div>
                            
                            @if ($addresses->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($addresses as $address)
                                        <label class="flex items-start p-4 border rounded-lg cursor-pointer hover:bg-gray-50 {{ $address->is_default ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                            <input type="radio" 
                                                   name="address_id" 
                                                   value="{{ $address->id }}" 
                                                   {{ $address->is_default ? 'checked' : '' }}
                                                   required
                                                   class="mt-1 text-blue-600">
                                            <div class="ml-3 flex-1">
                                                <div class="font-medium">{{ $address->label }}</div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $address->street }}, {{ $address->number }}
                                                    @if($address->complement)
                                                        - {{ $address->complement }}
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $address->neighborhood }} - {{ $address->city }}/{{ $address->state }}
                                                </div>
                                                <div class="text-sm text-gray-600">{{ $address->zip_code }}</div>
                                            </div>
                                            @if ($address->is_default)
                                                <span class="ml-2 px-2 py-1 bg-blue-200 text-blue-800 text-xs rounded">{{ __('Default') }}</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('address_id')" />
                            @else
                                <div class="text-center py-6 bg-yellow-50 border border-yellow-200 rounded">
                                    <p class="text-gray-700 mb-3">{{ __('You need to add a delivery address first.') }}</p>
                                    <a href="{{ route('addresses.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        {{ __('Add Address') }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Payment Method') }}</h3>
                            
                            <div class="space-y-3">
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="credit_card" checked required class="text-blue-600">
                                    <span class="ml-3">{{ __('Credit Card') }}</span>
                                </label>
                                
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="debit_card" required class="text-blue-600">
                                    <span class="ml-3">{{ __('Debit Card') }}</span>
                                </label>
                                
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="pix" required class="text-blue-600">
                                    <span class="ml-3">{{ __('PIX') }}</span>
                                </label>
                                
                                <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="payment_method" value="cash" required class="text-blue-600">
                                    <span class="ml-3">{{ __('Cash') }}</span>
                                </label>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('payment_method')" />
                        </div>

                        <!-- Order Notes -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Order Notes') }}</h3>
                            <textarea name="notes" 
                                      rows="3" 
                                      placeholder="{{ __('Any special instructions for your order?') }}"
                                      class="w-full border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>
                    </div>

                    <!-- Right Column: Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm p-6 sticky top-6">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Order Summary') }}</h3>

                            <!-- Cart Items -->
                            <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                                @foreach ($cart as $item)
                                    <div class="flex items-center text-sm">
                                        <div class="w-12 h-12 flex-shrink-0">
                                            @if ($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}" 
                                                     alt="{{ $item['name'] }}" 
                                                     class="w-full h-full object-cover rounded">
                                            @else
                                                <div class="w-full h-full bg-gray-200 rounded"></div>
                                            @endif
                                        </div>
                                        <div class="ml-3 flex-1">
                                            <div class="font-medium">{{ $item['name'] }}</div>
                                            <div class="text-gray-600">{{ $item['quantity'] }}x R$ {{ number_format($item['price'], 2, ',', '.') }}</div>
                                        </div>
                                        <div class="font-medium">
                                            R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Totals -->
                            <div class="border-t pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ __('Subtotal') }}</span>
                                    <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">{{ __('Delivery Fee') }}</span>
                                    <span>R$ {{ number_format($deliveryFee, 2, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold border-t pt-2">
                                    <span>{{ __('Total') }}</span>
                                    <span class="text-green-600">R$ {{ number_format($total, 2, ',', '.') }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-6 space-y-3">
                                @if ($addresses->count() > 0)
                                    <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 font-semibold">
                                        {{ __('Place Order') }}
                                    </button>
                                @endif
                                <a href="{{ route('cart.index') }}" class="block text-center px-6 py-3 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                    {{ __('Back to Cart') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
