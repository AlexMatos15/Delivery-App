<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Addresses') }}
            </h2>
            <a href="{{ route('addresses.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                {{ __('Add Address') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                <div class="p-6 text-gray-900">
                    @if ($addresses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($addresses as $address)
                                <div class="border rounded-lg p-4 {{ $address->is_default ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h3 class="font-bold text-lg">{{ $address->label }}</h3>
                                            @if ($address->is_default)
                                                <span class="inline-block mt-1 px-2 py-1 bg-blue-200 text-blue-800 text-xs rounded">
                                                    {{ __('Default') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-sm text-gray-700 space-y-1 mb-4">
                                        <p>{{ $address->street }}, {{ $address->number }}</p>
                                        @if ($address->complement)
                                            <p>{{ $address->complement }}</p>
                                        @endif
                                        <p>{{ $address->neighborhood }}</p>
                                        <p>{{ $address->city }} - {{ $address->state }}</p>
                                        <p>{{ $address->zip_code }}</p>
                                        @if ($address->reference)
                                            <p class="text-gray-600 italic">{{ __('Reference') }}: {{ $address->reference }}</p>
                                        @endif
                                    </div>

                                    <div class="flex gap-2">
                                        @if (!$address->is_default)
                                            <form method="POST" action="{{ route('addresses.set-default', $address) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-blue-600 hover:underline text-sm">
                                                    {{ __('Set as Default') }}
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('addresses.edit', $address) }}" class="text-blue-600 hover:underline text-sm">
                                            {{ __('Edit') }}
                                        </a>

                                        <form method="POST" action="{{ route('addresses.destroy', $address) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('{{ __('Delete this address?') }}')"
                                                    class="text-red-600 hover:underline text-sm">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ __('No addresses yet') }}</h3>
                            <p class="mt-2 text-gray-600">{{ __('Add a delivery address to place orders.') }}</p>
                            <a href="{{ route('addresses.create') }}" class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                {{ __('Add Your First Address') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
