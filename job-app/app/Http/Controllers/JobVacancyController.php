<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\JobVacancy;
use App\Models\JobApplication;
use App\Models\Resume;
use App\Http\Requests\ApplyJobRequest;
use App\Services\ResumeAnalysisService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JobVacancyController extends Controller
{
    protected $resumeAnalysisService;

    public function __construct(ResumeAnalysisService $resumeAnalysisService)
    {
        $this->resumeAnalysisService = $resumeAnalysisService;
    }

    public function index(): View
    {
        $jobVacancies = JobVacancy::with(['company', 'jobcategory'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('job-vacancies.index', compact('jobVacancies'));
    }

    public function show(string $id): View
    {
        $jobVacancy = JobVacancy::with(['company', 'jobcategory'])->findOrFail($id);
        return view('job-vacancies.show', compact('jobVacancy'));
    }

    public function apply(string $id): View
    {
        $jobVacancy = JobVacancy::with(['company', 'jobcategory'])->findOrFail($id);
        $resumes = Resume::where('userid', Auth::id())->orderBy('updated_at', 'desc')->get();
        return view('job-vacancies.apply', compact('jobVacancy', 'resumes'));
    }

    public function processApplication(ApplyJobRequest $request, string $id): RedirectResponse
    {
        try {
            // Test log
            Log::info('processApplication method called for job ID: ' . $id);

        // Check if user already applied for this job
        $existingApplication = JobApplication::where('userid', Auth::id())
            ->where('jobvacancyid', $id)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }

        $resumeId = null;
        $extractedInfo = null;

        // Handle resume selection
        $resumeOption = $request->input('resume_option');

        Log::info('Resume option received: ' . $resumeOption);
        Log::info('Request data: ' . json_encode($request->all()));

        if (str_starts_with($resumeOption, 'existing_')) {
            // Use existing resume
            $resumeId = str_replace('existing_', '', $resumeOption);

            // Verify the resume belongs to the user
            $resume = Resume::where('id', $resumeId)
                          ->where('userid', Auth::id())
                          ->first();

            if (!$resume) {
                return redirect()->back()->with('error', 'Selected resume not found.');
            }

            // Extract information from existing resume
            $extractedInfo = [
                'summary' => $resume->summary,
                'skills' => $resume->skills,
                'experience' => $resume->experience,
                'education' => $resume->education
            ];

        } elseif ($resumeOption === 'new_resume') {
            // Upload new resume
            Log::info('New resume upload started');
            Log::info('Has file check: ' . ($request->hasFile('resume_file') ? 'YES' : 'NO'));

            if (!$request->hasFile('resume_file')) {
                Log::error('No resume file found in request');
                return redirect()->back()->with('error', 'Please select a resume file to upload.');
            }

            $file = $request->file('resume_file');
            Log::info('Resume file received: ' . $file->getClientOriginalName());
            $extension = $file->getClientOriginalExtension();
            $originalFileName = $file->getClientOriginalName();
            $fileName = 'resume_' . time() . '.' . $extension;

            // Store in Laravel cloud storage
            Log::info('Storing file to cloud storage...');
            $path = $file->storeAs('resumes', $fileName, 'cloud');
            Log::info('File stored at path: ' . $path);

            // Extract information from the resume using ResumeAnalysisService with OpenAI
            try {
                // Get full file URL for OpenAI analysis
                $fileUrl = config('filesystems.disks.cloud.url') . '/' . $path;
                $extractedInfo = $this->resumeAnalysisService->extractResumeInformation($fileUrl);

                // Fallback to basic analysis if OpenAI fails
                if (empty($extractedInfo['summary']) && empty($extractedInfo['skills'])) {
                    $text = $this->resumeAnalysisService->extractTextFromPdf($file);
                    $analysis = $this->resumeAnalysisService->analyzeResumeText($text);

                    $extractedInfo = [
                        'summary' => $analysis['summary'],
                        'skills' => implode(', ', $analysis['skills']),
                        'experience' => json_encode($analysis['experience']),
                        'education' => json_encode($analysis['education'])
                    ];
                }
            } catch (\Exception $e) {
                // Fallback if PDF extraction fails
                $extractedInfo = [
                    'summary' => 'PDF analizi başarısız: ' . $e->getMessage(),
                    'skills' => '',
                    'experience' => '',
                    'education' => ''
                ];
            }

            // Create Resume record
            Log::info('Creating Resume record...');
            Log::info('Resume data: ' . json_encode([
                'filename' => $originalFileName,
                'fileurl' => $path,
                'userid' => Auth::id(),
                'summary' => substr($extractedInfo['summary'], 0, 100) . '...',
                'skills' => substr($extractedInfo['skills'], 0, 100) . '...'
            ]));

            $resume = Resume::create([
                'filename' => $originalFileName,
                'fileurl' => $path,
                'userid' => Auth::id(),
                'contactDetails' => json_encode([
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]),
                'summary' => $extractedInfo['summary'],
                'skills' => $extractedInfo['skills'],
                'experience' => $extractedInfo['experience'],
                'education' => $extractedInfo['education']
            ]);

            Log::info('Resume created with ID: ' . $resume->id);
            $resumeId = $resume->id;
        } else {
            return redirect()->back()->with('error', 'Please select a resume option.');
        }

        // TODO: Evaluate Job Application with AI
        $jobVacancy = JobVacancy::findOrFail($id);
        $evaluation = $this->resumeAnalysisService->analyzeResume($jobVacancy, $extractedInfo);

        Log::info('AI Evaluation completed', [
            'score' => $evaluation['aiGeneratedScore'] ?? 0,
            'feedback_length' => \strlen($evaluation['aiGeneratedFeedback'] ?? '')
        ]);

        // Create Job Application
        Log::info('Creating Job Application...');
        Log::info('Job Application data: ' . json_encode([
            'status' => 'pending',
            'jobvacancyid' => $id,
            'resumeid' => $resumeId,
            'userid' => Auth::id()
        ]));

        $jobApplication = JobApplication::create([
            'status' => 'pending',
            'jobvacancyid' => $id,
            'resumeid' => $resumeId,
            'userid' => Auth::id(),
            'aigeneratedscore' => $evaluation['aiGeneratedScore'] ?? 0,
            'aigeneratedfeedback' => $evaluation['aiGeneratedFeedback'] ?? ''
        ]);

        Log::info('Job Application created with ID: ' . $jobApplication->id);

        return redirect()->route('job-applications.index')
            ->with('success', 'Application submitted successfully');

    } catch (\Exception $e) {
        Log::error('Error in processApplication: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return redirect()->back()->with('error', 'An error occurred while processing your application: ' . $e->getMessage());
    }
}

}
