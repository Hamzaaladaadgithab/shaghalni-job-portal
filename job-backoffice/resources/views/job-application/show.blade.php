<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $job_application->user->name }} | Applied to {{ $job_application->jobVacancy->title }}

        </h2>
    </x-slot>

    <div class="overflow-x-auto p-6">

        <!--warapper-->
        <x-toast-notification />

        <!-- job application details section-->

        <div class="w-full mx-auto bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-semibold mb-4">Job Application Details:</h2>

            <div class="mb-4">
                <p><strong>Applicant Name:</strong> {{ $job_application->user->name }}</p>
            </div>

            <div class="mb-4">
                <p><strong>Job Vacancy:</strong> {{ $job_application->jobVacancy->title }}</p>
            </div>

            <div class="mb-4">
                <p><strong>Company:</strong> {{ $job_application->jobVacancy->company->name }}</p>
            </div>

            <div class="mb-4">
                <p><strong>Status:</strong> <span class="px-6 py-4 @if($job_application->status == 'accepted') text-green-500 @elseif($job_application->status == 'reviewed')
                        text-blue-500 @elseif($job_application->status == 'rejected') text-red-500 @elseif($job_application->status == 'pending') text-yellow-500
                        @endif whitespace-nowrap text-sm text-gray-900">{{ $job_application->status }}</span></p>
            </div>

            <div class="mb-4">
                <p><strong>Resume: </strong><a class="text-blue-600 hover:text-blue-900 underline"
                href="{{ $job_application->resume->fileurl }}" target="_blank">{{ $job_application->resume->fileurl }}</a></p>
            </div>


            <!-- edit and archive buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('job-application.edit', ['job_application' => $job_application->id,'redirectToList' =>'false']) }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <form action="{{ route('job-application.destroy', $job_application->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to archive this job application?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Archive
                    </button>
                </form>
                <a href="{{route('job-application.index')}}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back To List
                </a>
    </div>

    <!--tabs navigation-->
    <div class="mt-6 ">

        <ul class="flex space-x-4">
        <li>
            <a href="{{route('job-application.show', ['job_application' => $job_application->id, 'tab' => 'resume'])}}"
            class="px-4 py-2 text-gray-800 font-semibold {{ request('tab') == 'resume' || request('tab') == '' ? 'bg-blue-500 text-white' : '' }}">Resume</a>
        </li>

            <!--applications tab-->

        <li >
            <a href="{{route('job-application.show', ['job_application' => $job_application->id, 'tab' => 'AİFeedback'])}}"
            class="px-4 py-2 text-gray-800 font-semibold {{request('tab')=='AİFeedback' ? 'bg-blue-500 text-white' : '' }} ">Aİ Feedback</a>
        </li>

        </ul>
        </div>

        <!--tab contents-->

        <div class="mt-4">

        <!--resume tab-->
        <div id="resume" class="{{request('tab') == 'resume' || request('tab') == '' ?  'block' : 'hidden' }}">

            <table class="min-w-full  bg-gray-50 rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Summary</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Skills</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Experience</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Education</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                            <td class="px-6 py-4  text-sm text-gray-900">{{ $job_application->resume->summary }}</td>
                            <td class="px-6 py-4  text-sm text-gray-900">{{ $job_application->resume->skills }}</td>
                            <td class="px-6 py-4  text-sm text-gray-900">{{ $job_application->resume->experience }}</td>
                            <td class="px-6 py-4  text-sm text-gray-900">{{ $job_application->resume->education }}</td>
                        </tr>
                </tbody>
            </table>
        </div>

        <!--Ai Feedback tab-->
        <div id="AİFeedback" class="{{request('tab') == 'AİFeedback'  ? 'block' : 'hidden' }}" >

            <table class="min-w-full  bg-gray-50 rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Aİ Score</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Feedback</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Loop through applications associated with the company's jobs -->

                                <tr>
                            <td class="px-6 py-4  text-sm text-gray-900">{{ $job_application->aigeneratedscore }}</td>
                            <td class="px-6 py-4  text-sm text-gray-900">{{ $job_application->aigeneratedfeedback }}</td>
                        </tr>
                </tbody>
            </table>


        </div>
        </div>
        </div>
    </div>

</x-app-layout>

