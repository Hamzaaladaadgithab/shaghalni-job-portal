<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ request()->input('archived') == 'true' ? '(Archived)' : '(Active)' }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />



        <div class="flex justify-end items-center mb-4 space-x-4">
            @if(request()->input('archived') == 'true')
                <a href="{{ route('user.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Active Users
                </a>
            @else
                <a href="{{ route('user.index', ['archived' => 'true']) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Archived Users
                </a>
            @endif
        </div>

        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow mt-4 bg-white">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Role</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">

                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">

                                <span class="text-gray-500">{{ $user->name }}</span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->role }}</td>


                    <!-- restore -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(request()->input('archived') == 'true')
                                <form action="{{ route('user.restore', $user->id) }}" method="POST" class="inline">

                                    @csrf
                                    @method('PUT') <button type="submit" class="text-green-600 hover:text-green-900"
                                            onclick="return confirm('Are you sure you want to restore this category?')">♻️ Restore</button>
                                </form>
                            @else

                            <!--if admin  do not allow edit  or delete -->
                            @if($user->role != 'admin')
                            <!-- edit -->
                                <a href="{{ route('user.edit', [ $user->id]) }}"
                                    class="text-blue-600 hover:text-blue-900 mr-4">✍️ Edit</a>


                        <!-- Archive button -->

                                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline">

                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this category?')">🗃️ Archive</button>
                                </form>
                            @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">No Users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
