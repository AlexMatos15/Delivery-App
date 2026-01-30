<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categories') }}
            </h2>
            <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                {{ __('New Category') }}
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
                                    <th class="px-4 py-2 text-left">{{ __('Name') }}</th>
                                    <th class="px-4 py-2 text-left">{{ __('Slug') }}</th>
                                    <th class="px-4 py-2 text-center">{{ __('Products') }}</th>
                                    <th class="px-4 py-2 text-center">{{ __('Order') }}</th>
                                    <th class="px-4 py-2 text-center">{{ __('Status') }}</th>
                                    <th class="px-4 py-2 text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3">{{ $category->name }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $category->slug }}</td>
                                        <td class="px-4 py-3 text-center">{{ $category->products_count ?? $category->products->count() }}</td>
                                        <td class="px-4 py-3 text-center">{{ $category->order }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if ($category->is_active)
                                                <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-xs font-bold">{{ __('Active') }}</span>
                                            @else
                                                <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-xs font-bold">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center space-x-2">
                                            <a href="{{ route('admin.categories.edit', $category) }}" class="text-blue-600 hover:underline">
                                                {{ __('Edit') }}
                                            </a>
                                            
                                            <form method="POST" action="{{ route('admin.categories.toggle', $category) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-600 hover:underline">
                                                    {{ $category->is_active ? __('Deactivate') : __('Activate') }}
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" style="display: inline;" onsubmit="return confirm('{{ __('Are you sure?') }}');">
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
                                            {{ __('No categories found.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
