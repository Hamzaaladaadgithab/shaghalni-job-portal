<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $jobvacancy->title }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        <div class="w-full mx-auto bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-semibold mb-4"> Job Vacancy Information:</h2>

            <div class="mb-4">
                <p><strong>Title:</strong> {{ $jobvacancy->title }}</p>
            </div>
            <div class="mb-4">
                <p><strong>Description:</strong> {{ $jobvacancy->description }}</p>
            </div>
            <div class="mb-4">
                <p><strong>Location:</strong> {{ $jobvacancy->location }}</p>
            </div>
            <div class="mb-4">
                <p><strong>Type:</strong> {{ $jobvacancy->type }}</p>
            </div>
            <div class="mb-4">
                <p><strong>Salary:</strong> {{ $jobvacancy->salary }}</p>
            </div>
            <div class="mb-4">
                <p><strong>Company:</strong> {{ $jobvacancy->company?->name ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p><strong>Job Category:</strong> {{ $jobvacancy->jobcategory?->name ?? 'N/A' }}</p>
            </div>

            <div class="flex justify-end space-x-4">
                {{-- DÜZELTME: 'jobvacancy' -> 'job_vacancy' olarak değiştirildi --}}
                <a href="{{ route('job-vacancy.edit', ['job_vacancy' => $jobvacancy->id, 'redirectToList' => 'false']) }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>

                {{-- DÜZELTME: 'jobvacancy' -> 'job_vacancy' olarak değiştirildi --}}
                <form action="{{ route('job-vacancy.destroy', ['job_vacancy' => $jobvacancy->id]) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to archive this job vacancy?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Archive
                    </button>
                </form>

                <a href="{{ route('job-vacancy.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back To List
                </a>
            </div>
        </div>


        <div class="mt-6">
            <ul class="flex space-x-4">

            <li>
                    {{-- DÜZELTME: 'jobvacancy' -> 'job_vacancy' olarak değiştirildi --}}
                    <a href="{{ route('job-vacancy.show', ['job_vacancy' => $jobvacancy->id, 'tab' => 'applications']) }}"
                    class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'applications' || request('tab') == '' ? 'bg-blue-500 text-white' : '' }}">
                        Applications
                    </a>
                </li>
            </ul>
        </div>

        <div class="mt-4">
            <div id="jobs" class="{{ request('tab') == 'jobs' || request('tab') == '' ? 'block' : 'hidden' }}">
                <table class="min-w-full bg-gray-50 rounded-lg shadow">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Job Title</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Location</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jobvacancy->relatedJobs as $job)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->location }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job->type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{-- DÜZELTME: 'jobvacancy' -> 'job_vacancy' olarak değiştirildi --}}
                                    <a href="{{ route('job-vacancy.show', ['job_vacancy' => $job->id]) }}" class="text-blue-600 hover:text-blue-900 mr-4">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">No jobs found for this job vacancy.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="applications" class="{{ request('tab') == 'applications' ? 'block' : 'hidden' }}">
                <table class="min-w-full bg-gray-50 rounded-lg shadow">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Applicant Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Job Title</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($jobvacancy->jobapplications as $application)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->jobVacancy->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $application->status }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <a href="{{ route('job-application.show', $application->id) }}" class="text-blue-600 hover:text-blue-900 mr-4">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">No applications found for this job vacancy.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
