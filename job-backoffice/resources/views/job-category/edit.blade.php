<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job-Categories') }}
        </h2>
    </x-slot>


    <div class="overflow-x-auto p-6">

        <x-toast-notification />
        <div class="w-full mx-auto bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-semibold mb-4">Edit Job Category</h2>
            <form action="{{ route('job-category.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Category Name:</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" class="w-full px-3 py-2

                    border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('job-category.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">Cancel</a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Category</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
