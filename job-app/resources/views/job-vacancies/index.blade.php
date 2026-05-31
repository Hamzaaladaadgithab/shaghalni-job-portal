<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Browse Jobs') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-900">
        <!-- Success Message -->
        @if (session('success'))
            <div class="w-full bg-indigo-600 text-white p-4 mb-4">
                <div class="max-w-7xl mx-auto">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($jobVacancies as $job)
                        <div class="bg-gray-800 rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow">
                            <!-- Job Title and Company -->
                            <div class="mb-4">
                                <h3 class="text-white text-xl font-bold mb-2">
                                    {{ $job->title }}
                                </h3>
                                <p class="text-gray-300 text-sm mb-1">
                                    {{ $job->company->name ?? 'N/A' }}
                                </p>
                                <p class="text-gray-400 text-xs">
                                    {{ $job->location }}
                                </p>
                            </div>

                            <!-- Job Type and Category -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">
                                    {{ ucfirst($job->type) }}
                                </span>
                                @if($job->jobcategory)
                                    <span class="px-3 py-1 bg-green-600 text-white rounded-md text-sm">
                                        {{ $job->jobcategory->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Salary -->
                            @if($job->salary)
                                <div class="mb-4">
                                    <span class="text-gray-300 text-sm">Salary: </span>
                                    <span class="text-white text-sm font-medium">{{ $job->salary }}</span>
                                </div>
                            @endif

                            <!-- Description Preview -->
                            <div class="mb-4">
                                <p class="text-gray-400 text-sm line-clamp-3">
                                    {{ Str::limit(strip_tags($job->description), 120) }}
                                </p>
                            </div>

                            <!-- Posted Date -->
                            <div class="mb-4">
                                <span class="text-gray-400 text-xs">
                                    Posted {{ $job->created_at->diffForHumans() }}
                                </span>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-2">
                                <a href="{{ route('job-vacancies.show', $job->id) }}"
                                   class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    View Details
                                </a>
                                <a href="{{ route('job-vacancies.apply', $job->id) }}"
                                   class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-600 text-sm font-medium rounded-md text-gray-300 bg-transparent hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                    Apply Now
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full">
                            <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M8 6v10a2 2 0 002 2h4a2 2 0 002-2V6" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-white">No jobs available</h3>
                                    <p class="mt-1 text-sm text-gray-400">Check back later for new job opportunities.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if(method_exists($jobVacancies, 'links'))
                    <div class="mt-8">
                        <div class="bg-gray-800 rounded-lg p-4">
                            {{ $jobVacancies->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
