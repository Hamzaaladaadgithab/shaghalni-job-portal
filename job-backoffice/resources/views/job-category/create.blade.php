<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Job Category') }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <div class="w-full mx-auto p-6 bg-white rounded-lg shadow-md">
            <form method="POST" action="{{ route('job-category.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                        Category Name
                    </label>
                    <input
                        class="{{$errors->has('name') ? 'outline-red-500' : ''}} outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight
                        focus:outline-none focus:shadow-outline"
                        id="name" name="name" type="text" placeholder="Enter category name" required
                        value="{{ old('name') }}">

                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex  justify-end space-x-4">

                <a href="{{ route('job-category.index') }}"
                class=" px-2 py-2 inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 mr-4">
                ❌ Cancel
                </a>
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Add Category
                </button>

                </div>
            </form>
        </div>

        </div>

</x-app-layout>
