<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Job Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-green-900 border border-green-700 text-green-100 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-900 border border-red-700 text-red-100 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Welcome Section -->
            <div class="bg-gray-900 shadow-lg rounded-lg p-6 mb-6">
                <h3 class="text-white text-2xl font-bold mb-2">
                    Welcome, {{ auth()->user()->name ?? 'User' }}
                </h3>
                <p class="text-gray-300">Find your dream job from thousands of opportunities</p>
            </div>

            <!-- Search and Filters -->
            <div class="bg-gray-900 shadow-lg rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('dashboard') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div class="md:col-span-2">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search jobs..."
                                class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <!-- Job Type Filter -->
                        <div>
                            <select name="type" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Types</option>
                                @foreach($jobTypes as $type)
                                    <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div>
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors">
                                Search
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Filter Tags (Outside of form) -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('dashboard') }}" class="px-3 py-1 {{ !request('type') ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-700 hover:bg-gray-600' }} text-white rounded-full text-sm transition-colors">
                        All Time
                    </a>
                    <a href="{{ route('dashboard', ['type' => 'Full Time'] + request()->except('type')) }}" class="px-3 py-1 {{ request('type') == 'Full Time' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-700 hover:bg-gray-600' }} text-white rounded-full text-sm transition-colors">
                        Full Time
                    </a>
                    <a href="{{ route('dashboard', ['type' => 'Part Time'] + request()->except('type')) }}" class="px-3 py-1 {{ request('type') == 'Part Time' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-700 hover:bg-gray-600' }} text-white rounded-full text-sm transition-colors">
                        Part Time
                    </a>
                    <a href="{{ route('dashboard', ['type' => 'Remote'] + request()->except('type')) }}" class="px-3 py-1 {{ request('type') == 'Remote' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-700 hover:bg-gray-600' }} text-white rounded-full text-sm transition-colors">
                        Remote
                    </a>
                </div>
            </div>

            <!-- Job Listings -->
            <div class="space-y-4">
                @forelse($jobs as $job)
                    <div class="bg-gray-900 shadow-lg rounded-lg p-6 hover:bg-gray-800 transition-colors">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-xl font-semibold text-white">{{ $job->title }}</h3>
                                    <div class="flex gap-2">
                                        @if($job->type)
                                            <span class="px-3 py-1 bg-blue-600 text-white text-xs rounded-full">
                                                {{ $job->type }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center text-gray-300 mb-3">
                                    @if($job->company)
                                        <span class="font-medium">{{ $job->company->name }}</span>
                                        <span class="mx-2">•</span>
                                    @endif
                                    @if($job->location)
                                        <span>{{ $job->location }}</span>
                                    @endif
                                    @if($job->salary)
                                        <span class="mx-2">•</span>
                                        <span class="text-green-400 font-medium">${{ number_format($job->salary) }}</span>
                                    @endif
                                </div>

                                @if($job->description)
                                    <p class="text-gray-400 mb-4 line-clamp-2">
                                        {{ Str::limit($job->description, 150) }}
                                    </p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        @if($job->jobcategory)
                                            <span class="px-2 py-1 bg-gray-700 rounded text-xs">{{ $job->jobcategory->name }}</span>
                                        @endif
                                        <span class="ml-4">{{ $job->created_at->diffForHumans() }}</span>
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="{{ route('job-vacancies.show', $job->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gray-900 shadow-lg rounded-lg p-12 text-center">
                        <div class="text-gray-400 mb-4">
                            <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">No jobs found</h3>
                        <p class="text-gray-400">Try adjusting your search criteria or check back later for new opportunities.</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($jobs->hasPages())
                <div class="mt-8">
                    <div class="bg-gray-900 rounded-lg p-4">
                        {{ $jobs->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
