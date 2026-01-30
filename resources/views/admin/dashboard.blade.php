<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Orders -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">{{ __('Total Orders') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex gap-2 text-xs">
                        <span class="text-yellow-600">⏳ {{ $pendingOrders }} pending</span>
                        <span class="text-green-600">✓ {{ $deliveredOrders }} delivered</span>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">{{ __('Clients') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Products -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">{{ __('Products') }}</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-600">
                        {{ $totalCategories }} categories
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">{{ __('Revenue') }}</p>
                            <p class="text-3xl font-bold text-green-600">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 text-xs text-gray-600">
                        R$ {{ number_format($pendingRevenue, 2, ',', '.') }} pending
                    </div>
                </div>
            </div>

            <!-- Order Status Distribution -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Orders by Status') }}</h3>
                    <div class="space-y-3">
                        @php
                            $statusLabels = [
                                'pending' => __('Pending'),
                                'confirmed' => __('Confirmed'),
                                'preparing' => __('Preparing'),
                                'out_for_delivery' => __('Out for Delivery'),
                                'delivered' => __('Delivered'),
                                'cancelled' => __('Cancelled'),
                            ];
                            $statusColors = [
                                'pending' => 'bg-yellow-500',
                                'confirmed' => 'bg-blue-500',
                                'preparing' => 'bg-purple-500',
                                'out_for_delivery' => 'bg-indigo-500',
                                'delivered' => 'bg-green-500',
                                'cancelled' => 'bg-red-500',
                            ];
                        @endphp
                        @foreach ($statusLabels as $status => $label)
                            @php
                                $count = $ordersByStatus[$status] ?? 0;
                                $percentage = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
                            @endphp
                            <div>
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="font-medium">{{ $label }}</span>
                                    <span class="text-gray-600">{{ $count }} ({{ number_format($percentage, 1) }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="{{ $statusColors[$status] }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Top Products') }}</h3>
                    @if ($topProducts->count() > 0)
                        <div class="space-y-3">
                            @foreach ($topProducts as $product)
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-600">{{ $product->category->name }}</p>
                                    </div>
                                    <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded">
                                        {{ $product->order_items_count }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-600">{{ __('No sales yet') }}</p>
                    @endif
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">{{ __('Recent Orders') }}</h3>
                    <a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline text-sm">
                        {{ __('View All') }} →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold">{{ __('Order') }}</th>
                                <th class="px-4 py-3 text-left font-semibold">{{ __('Customer') }}</th>
                                <th class="px-4 py-3 text-left font-semibold">{{ __('Date') }}</th>
                                <th class="px-4 py-3 text-center font-semibold">{{ __('Status') }}</th>
                                <th class="px-4 py-3 text-right font-semibold">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:underline font-medium">
                                            {{ $order->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">{{ $order->user->name }}</td>
                                    <td class="px-4 py-3">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-center">
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
                                        <span class="px-2 py-1 rounded text-xs font-bold {{ $statusColors[$order->status] ?? 'bg-gray-200 text-gray-800' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium">
                                        R$ {{ number_format($order->total, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        {{ __('No orders yet') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
