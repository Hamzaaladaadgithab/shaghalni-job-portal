<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Requests\JobApplicationUpdateRequest;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function index(Request $request)
    {
        // (index method remains unchanged)
        //active
        $query = JobApplication::latest();

        // Filter for company-owner to see only applications for their company's jobs
        if(Auth::user()->role == 'company-owner'){
            $query->whereHas('jobvacancy', function($q) {
                $q->whereHas('company', function($companyQuery) {
                    $companyQuery->where('ownerid', Auth::user()->id);
                });
            });
        }

        //archived
        if($request->input('archived')==true) {
            $query->onlyTrashed();
        }

        $job_application = $query->paginate(10)->onEachSide(1);

        return view('job-application.index', compact('job_application'));
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job_application = JobApplication::with(['user', 'jobvacancy.company'])
            ->findOrFail($id);
        return view('job-application.show', compact('job_application'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $job_application = JobApplication::with(['user', 'jobvacancy.company'])
            ->findOrFail($id);
        return view('job-application.edit', compact('job_application'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobApplicationUpdateRequest $request, string $id)
    {
        $job_application = JobApplication::findOrFail($id);
        $job_application->update([
            'status' => $request->input('status'),
        ]);

         if($request->query('redirectToList') == 'false'){

         // Redirect to the job application's detail page
         return redirect()->route('job-application.show', $id)->with('success', 'Job application updated successfully.');
          }
        return redirect()->route('job-application.index')->with('success', 'Job application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job_application = JobApplication::findOrFail($id);
        $job_application->delete();
        return redirect()->route('job-application.index')->with('success', 'Job application deleted successfully.');
    }

    public function restore(string $id)
    {
        $job_application = JobApplication::withTrashed()->findOrFail($id);
        $job_application->restore();
        return redirect()->route('job-application.index')->with('success', 'Job application restored successfully.');
    }
}
