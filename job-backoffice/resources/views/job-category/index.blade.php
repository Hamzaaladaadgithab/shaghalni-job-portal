<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Job-Categories  {{request()->input('archived')== 'true' ? '(Archived)' : '(Active)'}}

        </h2>
    </x-slot>





    <div class="overflow-x-auto p-6">

        <x-toast-notification />


    <div class="flex justify-end items-center mb-4 space-x-4">

        @if(request()->has('archived') && request()->input('archived')==true)

        <!--active-->
            <a href="{{ route('job-category.index') }}" class="bg-blue-500 hover:bg-blue-700
            text-white font-bold py-2 px-4 rounded">
                Active Categories
            </a>

        @else

    <!-- archived-->
            <a href="{{ route('job-category.index',['archived' => 'true']) }}" class="bg-blue-500 hover:bg-blue-700
            text-white font-bold py-2 px-4 rounded">
                Archived Categories
            </a>

        @endif




        <!-- Add job category -->

            <a href="{{ route('job-category.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Add Job Category
            </a>



    </div>









        <!-- job category Table Section -->
        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow mt-4 bg-white">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Category Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Actions</th>

                </tr>


            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($categories as $category)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->name }}</td>


                        <!-- edit-->

                        <div class="flex space-x-4">
                        @if(request()->input('archived')==true)

                        <!--restore button-->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <form action="{{ route('job-category.restore', $category->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900"
                                onclick="return confirm('Are you sure you want to restore this category?')">♻️ Restore</button>

                            </form>
                        </td>



                    @else

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('job-category.edit', $category->id) }}"
                                class="text-blue-600 hover:text-blue-900 mr-4">✍️ Edit</a>

                            <!-- delete-->

                            <form action="{{ route('job-category.destroy', $category->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900"
                                onclick="return confirm('Are you sure you want to delete this category?')">🗃️ Archive</button>

                            </form>
                            @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">No job categories found.</td>
                    </tr>

                @endforelse
            </tbody>
        </table>


        <div class="mt-4">
            {{ $categories->links() }}
        </div>

    </div>
</x-app-layout>
