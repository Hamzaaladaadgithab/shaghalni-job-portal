<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('My Applications') }}
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
                <div class="space-y-6">
                    @forelse ($applications as $application)
                        <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
                            <!-- Job Title and Company -->
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-white text-xl font-bold mb-2">
                                        {{ $application->jobvacancy->title ?? 'N/A' }}
                                    </h3>
                                    <p class="text-gray-300 text-sm mb-1">
                                        {{ $application->jobvacancy->company->name ?? 'N/A' }}
                                    </p>
                                    <p class="text-gray-400 text-xs">
                                        {{ $application->jobvacancy->location ?? 'N/A' }}
                                    </p>
                                    <p class="text-gray-400 text-xs mt-1">
                                        {{ $application->created_at->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="flex flex-col items-end gap-2">
                                    <span class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">
                                        {{ $application->jobvacancy->type ?? 'Full-Time' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Applied With Resume -->
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-gray-300 text-sm">Applied With:</span>
                                <span class="text-white text-sm">
                                    {{ $application->resume->filename ?? 'Resume file' }}
                                </span>
                                @if($application->resume && $application->resume->fileurl)
                                    <a href="{{ $application->resume->getResumeUrl() }}"
                                       target="_blank"
                                       class="text-indigo-400 hover:text-indigo-300 text-sm underline">
                                        View Resume
                                    </a>
                                @endif
                            </div>

                            <!-- Status and Score -->
                            <div class="flex flex-col gap-3 mb-4">
                                <div class="flex items-center gap-4">
                                    @php
                                        $status = $application->status;
                                        $statusClass = match ($status) {
                                            'pending' => 'bg-yellow-500',
                                            'shortlisted' => 'bg-green-500',
                                            'accepted' => 'bg-green-500',
                                            'rejected' => 'bg-red-500',
                                            'reviewing' => 'bg-blue-500',
                                            'interview' => 'bg-purple-500',
                                            default => 'bg-gray-500',
                                        };
                                    @endphp
                                    <span class="px-3 py-1 {{ $statusClass }} text-white rounded-md text-sm font-medium">
                                        Status: {{ ucfirst($application->status) }}
                                    </span>

                                    @if($application->aigeneratedscore > 0)
                                        <span class="px-3 py-1 bg-indigo-600 text-white rounded-md text-sm">
                                            Score: {{ $application->aigeneratedscore }}%
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- AI Feedback -->
                            @if($application->aigeneratedfeedback)
                                <div class="mt-4">
                                    <h4 class="text-white text-md font-bold mb-2">AI Feedback:</h4>
                                    <div class="bg-gray-700 rounded-md p-4">
                                        <p class="text-gray-300 text-sm leading-relaxed">
                                            {{ $application->aigeneratedfeedback }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-white">No applications yet</h3>
                                <p class="mt-1 text-sm text-gray-400">Get started by applying to your first job.</p>
                                <div class="mt-6">
                                    <a href="{{ route('job-vacancies.index') }}"
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Browse Jobs
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if(method_exists($applications, 'links'))
                    <div class="mt-6">
                        <div class="bg-gray-800 rounded-lg p-4">
                            {{ $applications->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
