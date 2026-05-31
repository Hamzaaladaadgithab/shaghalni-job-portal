<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Job Vacancies {{ request()->input('archived') == 'true' ? '(Archived)' : '(Active)' }}
        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">
        <x-toast-notification />

        <div class="flex justify-end items-center mb-4 space-x-4">
            @if(request()->input('archived') == 'true')
                <a href="{{ route('job-vacancy.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Active Job Vacancies
                </a>
            @else
                <a href="{{ route('job-vacancy.index', ['archived' => 'true']) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Archived Job Vacancies
                </a>
            @endif

            <a href="{{ route('job-vacancy.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Add Job Vacancies
            </a>
        </div>

        <table class="min-w-full divide-y divide-gray-200 rounded-lg shadow mt-4 bg-white">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Job Title</th>
                    @if(auth()->user()->role == 'admin')
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Company Name</th>
                    @endif
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Location</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Type</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Salary</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobvacancies as $job_vacancy)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(request()->input('archived') == 'true')
                                <span class="text-gray-500 line-through">{{ $job_vacancy->title }}</span>
                            @else
                                <a class="text-blue-500 hover:text-blue-700"
                                    href="{{ route('job-vacancy.show', ['job_vacancy' => $job_vacancy->id]) }}">{{ $job_vacancy->title }}</a>
                            @endif
                        </td>
                        @if(auth()->user()->role == 'admin')
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job_vacancy->company?->name ?? 'N/A' }}</td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job_vacancy->location }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $job_vacancy->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($job_vacancy->salary, 2) }} USD</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if(request()->input('archived') == 'true')
                                <form action="{{ route('job-vacancy.restore', $job_vacancy->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT') <button type="submit" class="text-green-600 hover:text-green-900"
                                            onclick="return confirm('Are you sure you want to restore this category?')">♻️ Restore</button>
                                </form>
                            @else
                                <a href="{{ route('job-vacancy.edit', ['job_vacancy' => $job_vacancy->id]) }}" class="text-blue-600 hover:text-blue-900 mr-4">✍️ Edit</a>
                                <form action="{{ route('job-vacancy.destroy', $job_vacancy->id) }}" method="POST" class="inline">
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
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">No job vacancies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $jobvacancies->links() }}
        </div>
    </div>
</x-app-layout>
