<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Details') }} - {{ $order->order_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Order Header -->
                    <div class="flex justify-between items-start mb-6 pb-6 border-b">
                        <div>
                            <h3 class="text-2xl font-bold mb-2">{{ $order->order_number }}</h3>
                            <p class="text-gray-600">{{ __('Placed on') }} {{ $order->created_at->format('d/m/Y \a\t H:i') }}</p>
                        </div>
                        <div>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-200 text-yellow-800',
                                    'confirmed' => 'bg-blue-200 text-blue-800',
                                    'preparing' => 'bg-purple-200 text-purple-800',
                                    'out_for_delivery' => 'bg-indigo-200 text-indigo-800',
                                    'delivered' => 'bg-green-200 text-green-800',
                                    'cancelled' => 'bg-red-200 text-red-800',
                                ];
                                $statusLabels = [
                                    'pending' => __('Pending'),
                                    'confirmed' => __('Confirmed'),
                                    'preparing' => __('Preparing'),
                                    'out_for_delivery' => __('Out for Delivery'),
                                    'delivered' => __('Delivered'),
                                    'cancelled' => __('Cancelled'),
                                ];
                            @endphp
                            <span class="px-4 py-2 rounded text-sm font-bold {{ $statusColors[$order->status] ?? 'bg-gray-200 text-gray-800' }}">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-lg mb-4">{{ __('Items') }}</h4>
                        <div class="space-y-3">
                            @foreach ($order->items as $item)
                                <div class="flex items-center border-b pb-3">
                                    @if ($item->product && $item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                                             alt="{{ $item->product_name }}" 
                                             class="w-16 h-16 object-cover rounded">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded"></div>
                                    @endif
                                    <div class="ml-4 flex-1">
                                        <h5 class="font-medium">{{ $item->product_name }}</h5>
                                        <p class="text-sm text-gray-600">
                                            R$ {{ number_format($item->product_price, 2, ',', '.') }} x {{ $item->quantity }}
                                        </p>
                                    </div>
                                    <div class="font-bold">
                                        R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Delivery Information -->
                    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-lg mb-3">{{ __('Delivery Address') }}</h4>
                            <p class="text-gray-700">{{ $order->delivery_address }}</p>
                        </div>
                        <div>
                            <h4 class="font-semibold text-lg mb-3">{{ __('Payment') }}</h4>
                            <p class="text-gray-700">
                                <span class="font-medium">{{ __('Method') }}:</span> 
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            </p>
                            <p class="text-gray-700">
                                <span class="font-medium">{{ __('Status') }}:</span> 
                                {{ ucfirst($order->payment_status) }}
                            </p>
                        </div>
                    </div>

                    @if ($order->notes)
                        <div class="mb-6">
                            <h4 class="font-semibold text-lg mb-3">{{ __('Notes') }}</h4>
                            <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ $order->notes }}</p>
                        </div>
                    @endif

                    <!-- Order Summary -->
                    <div class="border-t pt-6">
                        <div class="space-y-2 max-w-sm ml-auto">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Subtotal') }}</span>
                                <span>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ __('Delivery Fee') }}</span>
                                <span>R$ {{ number_format($order->delivery_fee, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold border-t pt-2">
                                <span>{{ __('Total') }}</span>
                                <span class="text-green-600">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 flex justify-between items-center">
                        <a href="{{ route('orders.index') }}" class="text-blue-600 hover:underline">
                            ← {{ __('Back to Orders') }}
                        </a>
                        @if (in_array($order->status, ['pending', 'confirmed']))
                            <form method="POST" action="{{ route('orders.cancel', $order) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        onclick="return confirm('{{ __('Cancel this order?') }}')"
                                        class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    {{ __('Cancel Order') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
