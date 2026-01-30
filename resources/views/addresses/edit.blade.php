<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Address') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('addresses.update', $address) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="label" :value="__('Label (e.g., Home, Work)')" />
                            <x-text-input id="label" name="label" type="text" class="mt-1 block w-full" :value="old('label', $address->label)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('label')" />
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="zip_code" :value="__('ZIP Code')" />
                                <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full" :value="old('zip_code', $address->zip_code)" required placeholder="00000-000" />
                                <x-input-error class="mt-2" :messages="$errors->get('zip_code')" />
                            </div>
                            <div>
                                <x-input-label for="state" :value="__('State')" />
                                <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', $address->state)" required maxlength="2" placeholder="SP" />
                                <x-input-error class="mt-2" :messages="$errors->get('state')" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="city" :value="__('City')" />
                            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $address->city)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('city')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="neighborhood" :value="__('Neighborhood')" />
                            <x-text-input id="neighborhood" name="neighborhood" type="text" class="mt-1 block w-full" :value="old('neighborhood', $address->neighborhood)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('neighborhood')" />
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="col-span-2">
                                <x-input-label for="street" :value="__('Street')" />
                                <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" :value="old('street', $address->street)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('street')" />
                            </div>
                            <div>
                                <x-input-label for="number" :value="__('Number')" />
                                <x-text-input id="number" name="number" type="text" class="mt-1 block w-full" :value="old('number', $address->number)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('number')" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="complement" :value="__('Complement (optional)')" />
                            <x-text-input id="complement" name="complement" type="text" class="mt-1 block w-full" :value="old('complement', $address->complement)" placeholder="Apt, Suite, Floor, etc." />
                            <x-input-error class="mt-2" :messages="$errors->get('complement')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="reference" :value="__('Reference Point (optional)')" />
                            <textarea id="reference" name="reference" rows="2" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Near the bakery, blue building, etc.">{{ old('reference', $address->reference) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('reference')" />
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_default" value="1" {{ old('is_default', $address->is_default) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Set as default address') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Address') }}</x-primary-button>
                            <a href="{{ route('addresses.index') }}" class="text-gray-600 hover:underline">{{ __('Cancel') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
