<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ $jobVacancy->title }} - Apply
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-gray-900 shadow-lg rounded-lg p-6 max-w-7xl mx-auto">
            <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}" class="text-blue-400 hover:underline mb-6 inline-block">
                ← Back to Job Details
            </a>

            <div class="border-b border-white/10 pb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">{{ $jobVacancy->title }}</h1>
                        <p class="text-md text-gray-400">{{ $jobVacancy->company->name ?? 'Company not specified' }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <p class="text-sm text-gray-400">{{ $jobVacancy->location ?? 'Location not specified' }}</p>
                            @if($jobVacancy->location && $jobVacancy->salary)
                                <p class="text-sm text-gray-400">•</p>
                            @endif
                            @if($jobVacancy->salary)
                                <p class="text-sm text-gray-400">${{ number_format($jobVacancy->salary) }}</p>
                            @endif
                            @if($jobVacancy->type)
                                <span class="text-sm bg-indigo-500 text-white px-2 py-1 rounded-lg ml-2">{{ $jobVacancy->type }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded-lg mb-6 mt-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded-lg mb-6 mt-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded-lg mb-6 mt-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('job-vacancies.process-application', $jobVacancy->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 mt-6" x-data="{ selectedOption: '', fileName: '', hasError: {{ $errors->has('resume_file') ? 'true' : 'false' }} }">
                @csrf

                <!-- Resume Selection -->
                <div>
                    <h3 class="text-xl font-semibold text-white mb-4">Choose Your Resume</h3>

                    <!-- List of Existing Resumes -->
                    @if($resumes->count() > 0)
                        <div class="mb-6">
                            <x-input-label value="Select from your existing resumes:" class="text-white mb-3" />
                            <div class="space-y-3">
                                @foreach($resumes as $resume)
                                    <div class="flex items-center gap-3 p-3 bg-gray-800 rounded-lg hover:bg-gray-700 transition">
                                        <input type="radio"
                                               name="resume_option"
                                               id="existing_{{ $resume->id }}"
                                               value="existing_{{ $resume->id }}"
                                               x-model="selectedOption"
                                               class="text-blue-500 focus:ring-blue-500 focus:ring-2" />
                                        <label for="existing_{{ $resume->id }}" class="text-white cursor-pointer flex-1">
                                            <div class="font-medium">{{ $resume->filename }}</div>
                                            <div class="text-gray-400 text-sm">Last updated: {{ $resume->updated_at->format('M d, Y') }}</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Upload New Resume -->
                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-4">
                            <input type="radio"
                                   name="resume_option"
                                   id="new_resume"
                                   value="new_resume"
                                   x-model="selectedOption"
                                   class="text-blue-500 focus:ring-blue-500 focus:ring-2" />
                            <x-input-label for="new_resume" value="Upload a new resume:" class="text-white cursor-pointer" />
                        </div>

                        <div x-show="selectedOption === 'new_resume'" x-transition class="ml-6">
                            <label for="resume_file" class="block text-white cursor-pointer">
                                <div class="border-2 border-dashed border-gray-600 rounded-lg p-8 hover:border-blue-500 transition text-center"
                                     :class="{ 'border-blue-500': fileName, 'border-red-500': hasError }">
                                    <input @change="fileName = $event.target.files[0]?.name || ''; selectedOption = 'new_resume'"
                                           type="file"
                                           name="resume_file"
                                           id="resume_file"
                                           class="hidden"
                                           accept=".pdf" />

                                    <div class="text-center">
                                        <template x-if="!fileName">
                                            <div>
                                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                </svg>
                                                <p class="text-gray-400">Click to upload PDF (Max 5MB)</p>
                                            </div>
                                        </template>
                                        <template x-if="fileName">
                                            <div>
                                                <svg class="w-12 h-12 mx-auto mb-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p x-text="fileName" class="text-blue-400 font-medium"></p>
                                                <p class="text-gray-400 text-sm mt-1">Click to change file</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </label>
                            @error('resume_file')
                                <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @error('resume_option')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-6">
                    <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white rounded-lg font-semibold text-lg transition-all duration-200 transform hover:scale-105">
                        Apply Now
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</x-app-layout>
