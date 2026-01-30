<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Order') }} #{{ $order->order_number }}
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline">
                ← {{ __('Back to Orders') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Order Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Items -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold">{{ __('Order Items') }}</h3>
                        </div>
                        <div class="p-6">
                            <table class="w-full">
                                <thead class="border-b">
                                    <tr>
                                        <th class="text-left py-2">{{ __('Product') }}</th>
                                        <th class="text-center py-2">{{ __('Quantity') }}</th>
                                        <th class="text-right py-2">{{ __('Price') }}</th>
                                        <th class="text-right py-2">{{ __('Subtotal') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="py-3">
                                                <div class="font-medium">{{ $item->product_name }}</div>
                                            </td>
                                            <td class="py-3 text-center">{{ $item->quantity }}</td>
                                            <td class="py-3 text-right">R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                                            <td class="py-3 text-right font-medium">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="border-t">
                                    <tr>
                                        <td colspan="3" class="py-3 text-right font-medium">{{ __('Subtotal') }}:</td>
                                        <td class="py-3 text-right">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="py-2 text-right font-medium">{{ __('Delivery Fee') }}:</td>
                                        <td class="py-2 text-right">R$ {{ number_format($order->delivery_fee, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr class="text-lg">
                                        <td colspan="3" class="py-3 text-right font-bold">{{ __('Total') }}:</td>
                                        <td class="py-3 text-right font-bold text-green-600">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Delivery Address -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold">{{ __('Delivery Address') }}</h3>
                        </div>
                        <div class="p-6">
                            <div class="text-sm space-y-1">
                                <p class="font-medium">{{ $order->address->label }}</p>
                                <p>{{ $order->address->street }}, {{ $order->address->number }}</p>
                                @if ($order->address->complement)
                                    <p>{{ $order->address->complement }}</p>
                                @endif
                                <p>{{ $order->address->neighborhood }}</p>
                                <p>{{ $order->address->city }}, {{ $order->address->state }}</p>
                                <p>{{ $order->address->zip_code }}</p>
                                @if ($order->address->reference_point)
                                    <p class="text-gray-600 mt-2">
                                        <span class="font-medium">{{ __('Reference') }}:</span> {{ $order->address->reference_point }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar: Status & Payment -->
                <div class="space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold">{{ __('Customer') }}</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Name') }}</p>
                                <p class="font-medium">{{ $order->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Email') }}</p>
                                <p class="font-medium">{{ $order->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Shop') }}</p>
                                <p class="font-medium">{{ $order->shop->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ __('Order Date') }}</p>
                                <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold">{{ __('Order Status') }}</h3>
                        </div>
                        <div class="p-6">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-200 text-yellow-800',
                                    'confirmed' => 'bg-blue-200 text-blue-800',
                                    'preparing' => 'bg-purple-200 text-purple-800',
                                    'out_for_delivery' => 'bg-indigo-200 text-indigo-800',
                                    'delivered' => 'bg-green-200 text-green-800',
                                    'cancelled' => 'bg-red-200 text-red-800',
                                ];
                            @endphp
                            <div class="mb-4">
                                <span class="px-4 py-2 inline-flex text-sm font-bold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-200 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>

                            @if (!in_array($order->status, ['delivered', 'cancelled']))
                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Update Status') }}</label>
                                    <select
                                        name="status"
                                        id="status"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-3"
                                    >
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                                        <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>{{ __('Preparing') }}</option>
                                        <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>{{ __('Out for Delivery') }}</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                    </select>
                                    <button
                                        type="submit"
                                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                                    >
                                        {{ __('Update Status') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b bg-gray-50">
                            <h3 class="text-lg font-semibold">{{ __('Payment') }}</h3>
                        </div>
                        <div class="p-6">
                            @php
                                $paymentColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'refunded' => 'bg-gray-100 text-gray-800',
                                ];
                            @endphp
                            <div class="space-y-3 mb-4">
                                <div>
                                    <p class="text-sm text-gray-600">{{ __('Method') }}</p>
                                    <p class="font-medium">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">{{ __('Status') }}</p>
                                    <span class="px-3 py-1 inline-flex text-xs font-bold rounded {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                            </div>

                            @if (!in_array($order->payment_status, ['paid', 'refunded']))
                                <form method="POST" action="{{ route('admin.orders.update-payment', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Update Payment') }}</label>
                                    <select
                                        name="payment_status"
                                        id="payment_status"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 mb-3"
                                    >
                                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                                    </select>
                                    <button
                                        type="submit"
                                        class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition"
                                    >
                                        {{ __('Update Payment') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
