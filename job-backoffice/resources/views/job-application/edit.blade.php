<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Edit Applicant Status') }}
</h2>
</x-slot>

<div class="overflow-x-auto p-6">
    <div class="w-full mx-auto p-6 bg-white rounded-lg shadow-md">
        <form method="POST" action="{{ route('job-application.update', ['job_application' => $job_application->id, 'redirectToList' => request()->query('redirectToList')]) }}">
            @csrf
            @method('PUT')

            <div class="mb-6 p-6 bg-gray-100 rounded-lg shadow-md">
                <h5 class="text-2xl font-semibold mb-4">Job Application Details</h5>


                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Applicant Name</label>
                    <span>{{ $job_application->user?->name ?? 'N/A' }}</span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Job Vacancy</label>
                    <span>{{ $job_application->jobvacancy?->title ?? 'N/A' }}</span>
                </div>

            <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Company</label>
                    <span>{{ $job_application->jobvacancy?->company?->name ?? 'N/A' }}</span>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">AI Generated Score </label>
                    <span>{{ $job_application->aigeneratedscore }}</span>
                </div>



                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">AI Generated Feedback</label>
                    <span>{{ $job_application->aigeneratedfeedback }}</span>
                </div>


                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="status">Status</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="pending" {{ old('status', $job_application->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewed" {{ old('status', $job_application->status) == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                        <option value="rejected" {{ old('status', $job_application->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="accepted" {{ old('status', $job_application->status) == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        </select>

                    @error('status')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                </div>



        <div class="flex justify-end space-x-4">

                <a href="{{ route('job-application.index') }}"
                class=" px-2 py-2 inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 mr-4">
                ❌ Cancel
                </a>

                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Update Job Application
                </button>

            </div>
        </form>
    </div>
</div>


</x-app-layout>
