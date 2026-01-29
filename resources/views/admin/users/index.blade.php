@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-2xl font-bold mb-6">{{ __('User Management') }}</h2>

                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">{{ __('Name') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('Email') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('Type') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('Status') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('Joined') }}</th>
                                <th class="px-4 py-2 text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3">{{ $user->name }}</td>
                                    <td class="px-4 py-3">{{ $user->email }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded text-xs font-bold 
                                            @if ($user->type === 'admin')
                                                bg-red-200 text-red-800
                                            @elseif ($user->type === 'shop')
                                                bg-blue-200 text-blue-800
                                            @else
                                                bg-gray-200 text-gray-800
                                            @endif
                                        ">
                                            {{ ucfirst($user->type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($user->is_active)
                                            <span class="px-2 py-1 bg-green-200 text-green-800 rounded text-xs font-bold">{{ __('Active') }}</span>
                                        @else
                                            <span class="px-2 py-1 bg-red-200 text-red-800 rounded text-xs font-bold">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600 dark:text-gray-400">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-center space-x-2">
                                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm px-2 py-1 rounded 
                                                @if ($user->is_active)
                                                    bg-yellow-500 hover:bg-yellow-600 text-white
                                                @else
                                                    bg-green-500 hover:bg-green-600 text-white
                                                @endif
                                            ">
                                                @if ($user->is_active)
                                                    {{ __('Deactivate') }}
                                                @else
                                                    {{ __('Activate') }}
                                                @endif
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm px-2 py-1 rounded bg-red-500 hover:bg-red-600 text-white">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                                        {{ __('No users found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
