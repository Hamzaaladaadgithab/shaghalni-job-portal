<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $company ? $company->name : 'Company Not Found' }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        @if(!$company)
            <div class="w-full mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>Error:</strong> No company found for your account. Please contact the administrator.
            </div>
        @else



        <!--company details section-->

        <div class="w-full mx-auto bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-semibold mb-4">Company Information:</h2>
            <div class="mb-4">
                <p><strong>Owner:</strong>
                    @if($company->owner)
                        {{ $company->owner->name }}
                    @else
                        <span class="text-red-500">Owner not found</span>
                    @endif
                </p>
            </div>

            <div class="mb-4">
                <p><strong>Email:</strong>
                    @if($company->owner)
                        {{ $company->owner->email }}
                    @else
                        <span class="text-red-500">Owner not found</span>
                    @endif
                </p>
            </div>

            <div class="mb-4">
                <p><strong>Address:</strong> {{ $company->address }}</p>
            </div>

            <div class="mb-4">
                <p><strong>Industry:</strong> {{ $company->industry }}</p>
            </div>

            <div class="mb-4">
                <p><strong>Website:</strong> <a href="{{ $company->website }}" class="text-blue-600 hover:text-blue-900 underline" target="_blank">{{ $company->website }}</a></p>
            </div>



            <!-- edit and archive buttons -->
            <div class="flex justify-end space-x-4">
                @if($company->owner)
                    @if(auth()->user()->role == 'admin')
                        <a href="{{ route('company.edit', ['company' => $company->id]) }}?redirectToList=false"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Admin Edit
                        </a>
                    @else
                        <a href="{{ route('my-company.edit') }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Company Edit
                        </a>
                    @endif
                @else
                    <span class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed">
                        Edit (Owner Missing)
                    </span>
                @endif




                @if(auth()->user()->role=='admin')
                <form action="{{ route('company.destroy', $company->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to archive this company?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Archive
                    </button>
                </form>
                @endif

                @if(auth()->user()->role == 'admin')
                    <a href="{{route('company.index')}}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back To List
                    </a>
                @else
                    <a href="{{route('dashboard')}}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Back To Dashboard (Role: {{ auth()->user()->role }})
                    </a>
                @endif
            </div>


    @if(auth()->user()->role == 'admin')
    <!--tabs navigation-->
    <div class="mt-6 ">

        <ul class="flex space-x-4">

        <li>
            <a href="{{route('company.show', ['company' => $company->id, 'tab' => 'jobs'])}}"
            class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'jobs' || request('tab') == '' ? 'bg-blue-500 text-white' : '' }}">Jobs</a>
        </li>

            <!--applications tab-->

        <li >
            <a href="{{route('company.show', ['company' => $company->id, 'tab' => 'applications'])}}"
            class="px-4 py-2 text-gray-800 font-semibold {{request('tab')=='applications' ? 'bg-blue-500 text-white' : '' }} ">Applications</a>
        </li>

        </ul>
        </div>

        <!--tab contents-->

        <div class="mt-4">

        <!--jobs tab-->
        <div id="jobs" class="{{request('tab') == 'jobs' || request('tab') == '' ?  'block' : 'hidden' }}">

            <table class="min-w-full  bg-gray-50 rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Job Title</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Location</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Type</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Actions</th>

                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Loop through jobs associated with the company -->
                    @forelse($company->jobVacancies as $job)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->location }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="{{ route('job-vacancy.show', $job->id) }}" class="text-blue-600 hover:text-blue-900 mr-4">View</a>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">No jobs found for this company.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!--applications tab-->
        <div id="applications" class="{{request('tab') == 'applications'  ? 'block' : 'hidden' }}" >

            <table class="min-w-full  bg-gray-50 rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">ApplicantName</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Job Title</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Actions</th>

                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Loop through applications associated with the company's jobs -->

                    @forelse($company->jobapplications as $application)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->user ? $application->user->name : 'Unknown User' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->jobVacancy ? $application->jobVacancy->title : 'Unknown Job' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <a href="{{ route('job-application.show', $application->id) }}"
                                class="text-blue-600 hover:text-blue-900 mr-4">View</a>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">No applications found for this company's jobs.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>


        </div>
        </div>

        @endif

        </div>

    </div>
    @endif

</x-app-layout>

