<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Job Applications {{ request()->input('archived') == 'true' ? '(Archived)' : '(Active)' }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />



        <div class="flex justify-end items-center mb-4 space-x-4">
            @if(request()->input('archived') == 'true')
                <a href="{{ route('job-application.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Active Job Applications
                </a>
            @else
                <a href="{{ route('job-application.index', ['archived' => 'true']) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Archived Job Applications
                </a>
            @endif
        </div>

        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow mt-4 bg-white">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Applicant Name</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Position(Job Vacancy)</th>
                    @if(auth()->user()->role == 'admin')
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Company</th>
                    @endif
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($job_application as $jobapplication)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(request()->input('archived') == 'true')
                                <span class="text-gray-500 line-through">{{ $jobapplication->user?->name ?? 'N/A' }}</span>
                            @else
                                <a class="text-blue-500 hover:text-blue-700"
                                    href="{{ route('job-application.show', ['job_application' => $jobapplication->id]) }}">{{ $jobapplication->user?->name ?? 'N/A' }}</a>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jobapplication->jobvacancy?->title ?? 'N/A' }}</td>

                        @if(auth()->user()->role == 'admin')

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $jobapplication->jobvacancy?->company?->name ?? 'N/A' }}</td>
                        @endif

                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $statusColor = match($jobapplication->status) {
                                    'accepted' => 'text-green-500',
                                    'reviewed' => 'text-blue-500',
                                    'rejected' => 'text-red-500',
                                    'pending' => 'text-yellow-500',
                                    default => 'text-gray-900'
                                };
                            @endphp
                            <span class="{{ $statusColor }}">
                                {{ $jobapplication->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(request()->input('archived') == 'true')
                                <form action="{{ route('job-application.restore', $jobapplication->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT') <button type="submit" class="text-green-600 hover:text-green-900"
                                            onclick="return confirm('Are you sure you want to restore this category?')">♻️ Restore</button>
                                </form>
                            @else
                                <a href="{{ route('job-application.edit', ['job_application' => $jobapplication->id]) }}" class="text-blue-600 hover:text-blue-900 mr-4">✍️ Edit</a>
                                <form action="{{ route('job-application.destroy', $jobapplication->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this category?')">🗃️ Archive</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">No job applications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $job_application->links() }}
        </div>
    </div>
</x-app-layout>
