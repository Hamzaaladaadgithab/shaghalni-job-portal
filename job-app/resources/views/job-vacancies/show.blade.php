<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ $jobVacancy->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-gray-900 shadow-lg rounded-lg p-6 max-w-7xl mx-auto">
            <a href="{{ route('dashboard') }}" class="text-blue-400 hover:underline mb-6 inline-block">
                ← Back to Jobs
            </a>

            <div class="border-b border-white/10 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $jobVacancy->title }}</h1>
                        <p class="text-gray-400">{{ $jobVacancy->company->name ?? 'Company not specified' }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <p class="text-gray-400">{{ $jobVacancy->location ?? 'Location not specified' }}</p>
                            @if($jobVacancy->location && $jobVacancy->type)
                                <span class="text-gray-400">•</span>
                            @endif
                            <p class="text-gray-400">{{ $jobVacancy->type ?? 'Type not specified' }}</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('job-vacancies.apply', $jobVacancy->id) }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                            Apply Now
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-8 mt-6">
                <div class="col-span-2">
                    <h2 class="text-lg font-bold text-white mb-4">Job Description</h2>
                    <div class="text-gray-400 leading-relaxed">
                        {!! nl2br(e($jobVacancy->description ?? 'No description available.')) !!}
                    </div>
                </div>

                <div class="col-span-1">
                    <h2 class="text-lg font-bold text-white mb-4">Job Overview</h2>
                    <div class="bg-gray-800 rounded-lg p-6 space-y-4">
                        <div>
                            <p class="text-gray-400 text-sm">Published Date</p>
                            <p class="text-white">{{ $jobVacancy->created_at->format('M d, Y') }}</p>
                        </div>

                        <div>
                            <p class="text-gray-400 text-sm">Company</p>
                            <p class="text-white">{{ $jobVacancy->company->name ?? 'Not specified' }}</p>
                        </div>

                        <div>
                            <p class="text-gray-400 text-sm">Location</p>
                            <p class="text-white">{{ $jobVacancy->location ?? 'Not specified' }}</p>
                        </div>

                        @if($jobVacancy->salary)
                        <div>
                            <p class="text-gray-400 text-sm">Salary</p>
                            <p class="text-white">${{ number_format($jobVacancy->salary) }}</p>
                        </div>
                        @endif

                        <div>
                            <p class="text-gray-400 text-sm">Type</p>
                            <p class="text-white">{{ $jobVacancy->type ?? 'Not specified' }}</p>
                        </div>

                        @if($jobVacancy->jobcategory)
                        <div>
                            <p class="text-gray-400 text-sm">Category</p>
                            <p class="text-white">{{ $jobVacancy->jobcategory->name }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Apply Button (Mobile) -->
                    <div class="mt-6 md:hidden">
                        <a href="{{ route('job-vacancies.apply', $jobVacancy->id) }}" class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors text-center block">
                            Apply Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
