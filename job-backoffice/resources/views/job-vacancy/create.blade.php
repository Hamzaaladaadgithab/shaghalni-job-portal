<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('Add Job Vacancy') }}
</h2>
</x-slot>

<div class="overflow-x-auto p-6">
    <div class="w-full mx-auto p-6 bg-white rounded-lg shadow-md">
        <!-- Form points to the store method for submission -->
        <form method="POST" action="{{ route('job-vacancy.store') }}">
            @csrf

            <!-- Job vacancy details section -->
            <div class="mb-6 p-6 bg-gray-100 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold mb-4">Job Vacancy Details</h3>

                <p class="text-gray-600">Enter the job vacancy details.</p>

                <!-- Title Input Field -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title</label>
                    <input
                        class="{{$errors->has('title') ? 'outline-red-500' : ''}} outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="title" name="title" type="text" placeholder="Enter job vacancy title" required
                        value="{{ old('title') }}">

                    @error('title')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location Input Field -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="location">Location</label>
                    <input
                        class="{{$errors->has('location') ? 'outline-red-500' : ''}} outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="location" name="location" type="text" placeholder="Enter job vacancy location" required
                        value="{{ old('location') }}">

                    @error('location')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Salary Input Field (Required to be numeric by validation) -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="salary">Expected Salary (USD)</label>
                    <input
                        class="{{$errors->has('salary') ? 'outline-red-500' : ''}} outline outline-1 mt-1 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="salary" name="salary" type="text" placeholder="Enter the salary"
                        value="{{ old('salary') }}">
                    <!-- Removed 'required' here to align with 'nullable' in the validation, but validation still checks 'numeric' -->
                    @error('salary')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type Dropdown -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="type">Type</label>
                    <select name="type" id="type" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <!-- NOTE: Values here must match the 'in' rule in JobVacancyCreateRequest (e.g., 'Full-Time') -->
                        <option value="Full-Time" {{ old('type') == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                        <option value="Part-Time" {{ old('type') == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                        <option value="Remote" {{ old('type') == 'Remote' ? 'selected' : '' }}>Remote</option>
                        <option value="Contract" {{ old('type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                        <!-- Changed values to match validation rules: Full-Time, Part-Time, Remote, Contract -->
                    </select>

                    @error('type')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- COMPANY select dropdown (CORRECTION: Renamed 'name' to 'company_id') -->
                <div class="mb-4">
                    <label for="company_id" class="block text-sm font-medium text-gray-700">Company</label>

                    <select name="company_id" id="company_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="" disabled selected>Select a Company</option>
                        @foreach($companies as $company)
                            <!-- Check old value for selection -->
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                        @endforeach
                    </select>
                     @error('company_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- job Category select dropdown (CORRECTION: Renamed 'name' to 'jobcategory_id') -->
                <div class="mb-4">
                    <label for="jobcategory_id" class="block text-sm font-medium text-gray-700">Job Category</label>

                    <select name="jobcategory_id" id="jobcategory_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                         <option value="" disabled selected>Select a Job Category</option>
                        @foreach($jobcategories as $jobcategory)
                            <!-- Check old value for selection -->
                            <option value="{{ $jobcategory->id }}" {{ old('jobcategory_id') == $jobcategory->id ? 'selected' : '' }}>{{ $jobcategory->name }}</option>
                        @endforeach
                    </select>
                     @error('jobcategory_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- job description Textarea (CORRECTION: Removed reference to $jobvacancy in create form) -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Job Description</label>

                    <textarea name="description" id="description"
                        rows="4" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required>{{ old('description') }}</textarea>


                     @error('description')
                        <p class="text-red-500 text-xs italic mt-2">{{$message}}</p>
                    @enderror
                </div>

            </div> <!-- End of Job Vacancy Details -->

            <div class="flex justify-end space-x-4">

                <!-- Cancel button -->
                <a href="{{route('job-vacancy.index')}}"
                class=" px-2 py-2 inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 mr-4">
                ❌ Cancel
                </a>

                <!-- Submit button -->
                <button
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                    type="submit">
                    Add Job Vacancy
                </button>

            </div>
        </form>
    </div>
</div>


</x-app-layout>
