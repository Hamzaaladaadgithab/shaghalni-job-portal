<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Edit Job Vacancy') }}
</h2>
</x-slot>

<div class="overflow-x-auto p-6">
    <div class="w-full mx-auto p-6 bg-white rounded-lg shadow-md">
        <form method="POST" action="{{ route('job-vacancy.update', ['job_vacancy' => $jobvacancy->id, 'redirectToList' => request()->query('redirectToList')]) }}">
            @csrf
            @method('PUT')

            <div class="mb-6 p-6 bg-gray-100 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold mb-4">Job Vacancy Details</h3>

                <p class="text-gray-600">Enter the job vacancy details.</p>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title</label>
                    <input
                        class="outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline {{ $errors->has('title') ? 'outline-red-500 outline-2' : '' }}"
                        id="title" name="title" type="text" placeholder="Enter job vacancy title" required
                        value="{{ old('title', $jobvacancy->title) }}">

                    @error('title')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="location">Location</label>
                    <input
                        class="outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline {{ $errors->has('location') ? 'outline-red-500 outline-2' : '' }}"
                        id="location" name="location" type="text" placeholder="Enter job vacancy location" required
                        value="{{ old('location', $jobvacancy->location) }}">

                    @error('location')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="salary">Expected Salary (USD)</label>
                    <input
                        class="outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline {{ $errors->has('salary') ? 'outline-red-500 outline-2' : '' }}"
                        id="salary" name="salary" type="text" placeholder="Enter the salary"
                        value="{{ old('salary', $jobvacancy->salary) }}">

                    @error('salary')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="type">Type</label>
                    <select name="type" id="type" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="Full-Time" {{ old('type', $jobvacancy->type) == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                        <option value="Part-Time" {{ old('type', $jobvacancy->type) == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                        <option value="Remote" {{ old('type', $jobvacancy->type) == 'Remote' ? 'selected' : '' }}>Remote</option>
                        <option value="Contract" {{ old('type', $jobvacancy->type) == 'Contract' ? 'selected' : '' }}>Contract</option>
                        </select>

                    @error('type')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="company_id" class="block text-sm font-medium text-gray-700">Company</label>

                    <select name="company_id" id="company_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="" disabled>Select a Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ (old('company_id') == $company->id || $jobvacancy->company_id == $company->id) ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                     @error('company_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jobcategory_id" class="block text-sm font-medium text-gray-700">Job Category</label>

                    <select name="jobcategory_id" id="jobcategory_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                           <option value="" disabled>Select a Job Category</option>
                        @foreach($jobcategories as $jobcategory)
                            <option value="{{ $jobcategory->id }}" {{ (old('jobcategory_id') == $jobcategory->id || $jobvacancy->jobcategory_id == $jobcategory->id) ? 'selected' : '' }}>{{ $jobcategory->name }}</option>
                        @endforeach
                    </select>
                     @error('jobcategory_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Job Description</label>

                    <textarea name="description" id="description"
                        rows="4" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required>{{ old('description', $jobvacancy->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

            </div> <div class="flex justify-end space-x-4">

                <a href="{{ route('job-vacancy.index') }}"
                class=" px-2 py-2 inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 mr-4">
                ❌ Cancel
                </a>

                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Upadate Job Vacancy
                </button>

            </div>
        </form>
    </div>
</div>


</x-app-layout>
