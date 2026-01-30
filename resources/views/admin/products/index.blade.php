<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products') }}
            </h2>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                {{ __('New Product') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('status'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">{{ __('Product') }}</th>
                                    <th class="px-4 py-2 text-left">{{ __('Category') }}</th>
                                    <th class="px-4 py-2 text-center">{{ __('Price') }}</th>
                                    <th class="px-4 py-2 text-center">{{ __('Stock') }}</th>
                                    <th class="px-4 py-2 text-center">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center">
                                                @if ($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-12 h-12 object-cover rounded mr-3">
                                                @endif
                                                <div>
                                                    <div class="font-medium">{{ $product->name }}</div>
                                                    @if ($product->is_featured)
                                                        <span class="text-xs text-yellow-600">★ Featured</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">{{ $product->category->name }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($product->promotional_price)
                                                <div class="text-gray-400 line-through text-xs">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                                                <div class="text-green-600 font-bold">R$ {{ number_format($product->promotional_price, 2, ',', '.') }}</div>
                                            @else
                                                <div>R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="px-2 py-1 rounded text-xs font-bold {{ $product->stock > 0 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($product->is_active)
                                                <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-xs font-bold">{{ __('Active') }}</span>
                                            @else
                                                <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-xs font-bold">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center space-x-2">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-600 hover:underline">
                                                {{ __('Edit') }}
                                            </a>
                                            
                                            <form method="POST" action="{{ route('admin.products.toggle', $product) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:underline">
                                                    {{ $product->is_active ? __('Deactivate') : __('Activate') }}
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display: inline;" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                            {{ __('No products found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
