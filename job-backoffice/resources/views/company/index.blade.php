<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Companies  {{request()->input('archived')== 'true' ? '(Archived)' : '(Active)'}}

        </h2>
    </x-slot>





    <div class="overflow-x-auto p-6">

        <x-toast-notification />


    <div class="flex justify-end items-center mb-4 space-x-4">

        @if(request()->has('archived') && request()->input('archived')==true)

        <!--active-->
            <a href="{{ route('company.index') }}" class="bg-blue-500 hover:bg-blue-700
            text-white font-bold py-2 px-4 rounded">
                Active Companies
            </a>

        @else

    <!-- archived-->
            <a href="{{ route('company.index',['archived' => 'true']) }}"
            class="bg-blue-500 hover:bg-blue-700
            text-white font-bold py-2 px-4 rounded">
                Archived Companies
            </a>

        @endif




        <!-- Add companies -->

            <a href="{{ route('company.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Add  Companies
            </a>



    </div>

        <!-- companies Table Section -->
        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow mt-4 bg-white">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Company Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Address</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Industry</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Website</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Actions</th>

                </tr>

            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- Loop through companies buradaki data gosterir databasden getirir -->
                @forelse($companies as $company)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">

                        @if(request()->input('archived')=='true')
                        <span class="text-gray-500 line-thruogh">{{$company->name}}</span>
                        @else
                        <a class="text-blue-500 hover:text-blue-700"
                        href="{{route('company.show',$company->id)}}">{{$company->name}} </a> </td>

                        @endif

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $company->address }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $company->industry }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ $company->website }}" class="text-blue-600 hover:text-blue-900 underline" target="_blank">
                                {{ $company->website }}
                            </a>
                        </td>





                        <!-- edit-->
                        <div class="flex space-x-4">
                        @if(request()->input('archived')==true)

                        <!--restore button-->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <form action="{{route('company.restore', $company->id)}}" method="POST"
                                class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900"
                                onclick="return confirm('Are you sure you want to restore this category?')">♻️ Restore</button>

                            </form>
                        </td>

                    @else
                    <!-- edit-->

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <a href="{{ route('company.edit', $company->id) }}"
                                class="text-blue-600 hover:text-blue-900 mr-4">✍️ Edit</a>

                            <!-- delete-->

                            <form action="{{ route('company.destroy', $company->id) }}" method="POST" class="inline">
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
                        <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">No job companies found.</td>
                    </tr>

                @endforelse
            </tbody>
        </table>


        <div class="mt-4">
            {{ $companies->links() }}
        </div>

    </div>
</x-app-layout>
