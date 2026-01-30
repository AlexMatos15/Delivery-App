<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Search') }}</label>
                        <input
                            type="text"
                            name="search"
                            id="search"
                            value="{{ request('search') }}"
                            placeholder="{{ __('Order number or customer name') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Status') }}</label>
                        <select
                            name="status"
                            id="status"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                            <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>{{ __('Preparing') }}</option>
                            <option value="out_for_delivery" {{ request('status') === 'out_for_delivery' ? 'selected' : '' }}>{{ __('Out for Delivery') }}</option>
                            <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>{{ __('Delivered') }}</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                        </select>
                    </div>

                    <!-- Payment Method Filter -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-1">{{ __('Payment Method') }}</label>
                        <select
                            name="payment_method"
                            id="payment_method"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        >
                            <option value="">{{ __('All Methods') }}</option>
                            <option value="credit_card" {{ request('payment_method') === 'credit_card' ? 'selected' : '' }}>{{ __('Credit Card') }}</option>
                            <option value="debit_card" {{ request('payment_method') === 'debit_card' ? 'selected' : '' }}>{{ __('Debit Card') }}</option>
                            <option value="pix" {{ request('payment_method') === 'pix' ? 'selected' : '' }}>{{ __('PIX') }}</option>
                            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                        >
                            {{ __('Filter') }}
                        </button>
                        <a
                            href="{{ route('admin.orders.index') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition"
                        >
                            {{ __('Clear') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('Order') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('Customer') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('Shop') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">{{ __('Date') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">{{ __('Status') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">{{ __('Payment') }}</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 uppercase">{{ __('Total') }}</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium text-gray-900">{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->shop->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
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
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-200 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @php
                                            $paymentColors = [
                                                'pending' => 'bg-yellow-100 text-yellow-800',
                                                'paid' => 'bg-green-100 text-green-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                                'refunded' => 'bg-gray-100 text-gray-800',
                                            ];
                                        @endphp
                                        <div>
                                            <span class="px-2 py-1 inline-flex text-xs font-bold rounded {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                            <div class="text-xs text-gray-500 mt-1">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-bold text-gray-900">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a
                                            href="{{ route('admin.orders.show', $order) }}"
                                            class="text-blue-600 hover:text-blue-800 font-medium"
                                        >
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium">{{ __('No orders found') }}</p>
                                        <p class="text-sm">{{ __('Try adjusting your filters') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($orders->hasPages())
                    <div class="px-6 py-4 border-t">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
