@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-2xl font-bold mb-6">{{ __('My Profile') }}</h2>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium">{{ __('Name') }}</label>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">{{ __('Email') }}</label>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ Auth::user()->email }}</p>
                        @if (Auth::user()->email_verified_at)
                            <p class="mt-1 text-sm text-green-600">{{ __('Email verified') }}</p>
                        @else
                            <p class="mt-1 text-sm text-yellow-600">{{ __('Email not verified') }}</p>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium">{{ __('User Type') }}</label>
                        <p class="mt-2 text-gray-700 dark:text-gray-300 capitalize">{{ Auth::user()->type }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">{{ __('Member Since') }}</label>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ Auth::user()->created_at->format('M d, Y') }}</p>
                    </div>

                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            {{ __('Edit Profile') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
