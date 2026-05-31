<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;
use App\Models\JobCategory;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

// Import the newly created Form Requests
use App\Http\Requests\JobVacancyCreateRequest;
use App\Http\Requests\JobVacancyUpdateRequest;

class JobVacancyController extends Controller
{

    public function index(Request $request)
    {
        // (index method remains unchanged)
        //active
        $query = JobVacancy::latest();

        // Filter for company-owner to see only their company's job vacancies
        if(Auth::user()->role == 'company-owner'){
            $query->whereHas('company', function($companyQuery) {
                $companyQuery->where('ownerid', Auth::user()->id);
            });
        }

        //archived
        if($request->input('archived')==true) {
            $query->onlyTrashed();
        }

        $jobvacancies = $query->paginate(10)->onEachSide(1);

        return view('job-vacancy.index', compact('jobvacancies'));
    }

    // ... (rest of the controller code)

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Fetch companies based on user role
        if(Auth::user()->role == 'company-owner'){
            // Company owner can only create job vacancies for their own company
            $companies = Company::where('ownerid', Auth::user()->id)->get();
        } else {
            // Admin can create job vacancies for any company
            $companies = Company::all();
        }

        $jobcategories = JobCategory::all();
        return view('job-vacancy.create', compact('companies', 'jobcategories'));
    }

    /**
     * Store a newly created resource in storage (CREATE operation).
     * We use JobVacancyCreateRequest to handle validation.
     */
    public function store(JobVacancyCreateRequest $request)
    {
        // Validation is automatically handled by the FormRequest class.
        // تم إلغاء التعليق وإكمال منطق دالة التخزين
        $data = $request->validated();

        JobVacancy::create($data); // Create and save the new job vacancy.

        // Redirect the user with a success message
        return redirect()->route('job-vacancy.index')->with('success', 'Job vacancy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $query = JobVacancy::query();

        // Filter for company-owner to see only their company's job vacancies
        if(Auth::user()->role == 'company-owner'){
            $query->whereHas('company', function($companyQuery) {
                $companyQuery->where('ownerid', Auth::user()->id);
            });
        }

        $jobvacancy = $query->findOrFail($id);
        return view('job-vacancy.show', compact('jobvacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // 1. Find the job vacancy record with company-owner filtering
        $query = JobVacancy::query();

        if(Auth::user()->role == 'company-owner'){
            $query->whereHas('company', function($companyQuery) {
                $companyQuery->where('ownerid', Auth::user()->id);
            });
        }

        $jobvacancy = $query->findOrFail($id);

        // 2. Fetch required related data for dropdowns based on user role
        if(Auth::user()->role == 'company-owner'){
            $companies = Company::where('ownerid', Auth::user()->id)->get();
        } else {
            $companies = Company::all();
        }

        $jobcategories = JobCategory::all();

        // 3. Return the edit view with all necessary data
        return view('job-vacancy.edit', compact('jobvacancy', 'companies', 'jobcategories'));
    }

    /**
     * Update the specified resource in storage (UPDATE operation).
     * We use JobVacancyUpdateRequest to handle validation.
     */
    public function update(JobVacancyUpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        // Find job vacancy with company-owner filtering
        $query = JobVacancy::query();

        if(Auth::user()->role == 'company-owner'){
            $query->whereHas('company', function($companyQuery) {
                $companyQuery->where('ownerid', Auth::user()->id);
            });
        }

        $jobvacancy = $query->findOrFail($id);

        //Validation is handled by FormRequest
        $jobvacancy->update($validated); // Update the record with validated data.

        if($request->query('redirectToList') == 'false'){
            // Redirect to the job vacancy's detail page
            return redirect()->route('job-vacancy.show', $id)->with('success', 'Job vacancy updated successfully.');
        }

        return redirect()->route('job-vacancy.show', $jobvacancy->id)->with('success', 'Job vacancy updated successfully.');
    }

    /**
     * Remove the specified resource from storage (Soft Delete operation).
     */
    public function destroy(string $id)
    {
        // Find job vacancy with company-owner filtering
        $query = JobVacancy::query();

        if(Auth::user()->role == 'company-owner'){
            $query->whereHas('company', function($companyQuery) {
                $companyQuery->where('ownerid', Auth::user()->id);
            });
        }

        $jobvacancy = $query->findOrFail($id);
        $jobvacancy->delete();

        return redirect()->route('job-vacancy.index')->with('success', 'Job vacancy deleted successfully');
    }


    public function restore(string $id)
    {
        $jobvacancy = JobVacancy::withTrashed()->findOrFail($id);
        $jobvacancy->restore();
        // تم التصحيح
        return redirect()->route('job-vacancy.index',['archived' =>'true'])->with('success', 'Job vacancy restored successfully');

    }
}
