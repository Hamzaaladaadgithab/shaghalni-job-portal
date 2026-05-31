<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class JobApplicationsController extends Controller
{
    public function index(): View
    {
        $applications = JobApplication::with(['jobvacancy.company'])
            ->where('userid', Auth::id())
            ->latest()
            ->paginate(10);

        return view('job-applications.index', compact('applications'));
    }

    public function show(string $id): View
    {
        $application = JobApplication::with(['jobvacancy.company', 'resume'])
            ->where('userid', Auth::id())
            ->findOrFail($id);

        return view('job-applications.show', compact('application'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_vacancy_id' => 'required|exists:job_vacancies,id'
        ]);

        // Check if user already applied for this job
        $existingApplication = JobApplication::where('userid', Auth::id())
            ->where('jobvacancyid', $validated['job_vacancy_id'])
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }

        // Create application with all required fields
        JobApplication::create([
            'userid' => Auth::id(),
            'jobvacancyid' => $validated['job_vacancy_id'],
            'status' => 'pending',
            'resumeid' => null,
            'aigeneratedscore' => null,
            'aigeneratedfeedback' => null,
        ]);

        return redirect()->back()->with('success', 'Application submitted successfully!');
    }
}
