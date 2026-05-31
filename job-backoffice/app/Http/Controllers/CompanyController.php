<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CompanyCreateRequest;



class CompanyController extends Controller
{





public $industries =['Technology', 'Finance','Healthcare','Education','Manufacturing','Ratail','other'];





    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {  //active
        $query = Company::latest();

        //archived
        if($request->input('archived')==true) {
            $query->onlyTrashed();
        }

        $companies = $query->paginate(10)->onEachSide(1);

        return view('company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $industries =$this->industries;

        return view('company.create',compact('industries'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyCreateRequest $request)
    {
        $validated = $request->validated();

        //create owner
        $owner = User::create([
            'name' => $validated['owner_name'],
            'email' => $validated['owner_email'],
            'password' => Hash::make($validated['owner_password']),
            'role' => 'company-owner',
        ]);


        //return error if owner creation fails
        if(!$owner){
            return redirect()->route('company.create')->with('error', 'Failed to create owner.');
        }


        //create company
        Company::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'industry' => $validated['industry'],
            'website' => $validated['website'],
            'ownerid' => $owner->id,
        ]);

        return redirect()->route('company.index')->with('success', 'Company created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(?string $id = null)
    {
        if($id) {
            $company = Company::with(['owner', 'jobapplications.user', 'jobapplications.jobVacancy'])->findOrFail($id);
        } else {
            // Check if user is authenticated
            if (!Auth::check()) {
                abort(401, 'Unauthorized');
            }

            $company = Company::with(['owner', 'jobapplications.user', 'jobapplications.jobVacancy'])->where('ownerid', Auth::user()->id)->first();

            // If no company found, redirect with error
            if (!$company) {
                return redirect()->route('dashboard')->with('error', 'No company found for your account. Please contact administrator.');
            }
        }
        return view('company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(?string $id = null)
    {
        if ($id) {
            // Admin editing specific company
            $company = Company::with('owner')->findOrFail($id);
        } else {
            // Company owner editing their own company
            if (!Auth::check()) {
                abort(401, 'Unauthorized');
            }

            $company = Company::with('owner')->where('ownerid', Auth::user()->id)->first();

            if (!$company) {
                return redirect()->route('dashboard')->with('error', 'No company found for your account.');
            }
        }

        $industries = $this->industries;
        return view('company.edit', compact('company','industries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, ?string $id = null)
    {
        $validated = $request->validated();

        if ($id) {
            // Admin updating specific company
            $company = Company::findOrFail($id);
        } else {
            // Company owner updating their own company
            if (!Auth::check()) {
                abort(401, 'Unauthorized');
            }

            $company = Company::where('ownerid', Auth::user()->id)->first();

            if (!$company) {
                return redirect()->route('dashboard')->with('error', 'No company found for your account.');
            }
        }

        $company->update([
            'name'=>$validated['name'],
            'address'=>$validated['address'],
            'industry'=>$validated['industry'],
            'website'=>$validated['website'],
        ]);

        //update owner
        if ($company->owner) {
            $ownerData = [];
            $ownerData['name'] = $validated['owner_name'];

            if (!empty($validated['owner_password'])) {
                $ownerData['password'] = Hash::make($validated['owner_password']);
            }

            $company->owner->update($ownerData);
        }

        if ($id) {
            // Admin flow
            if($request->query('redirectToList')=='false'){
                return redirect()->route('company.show',$id)->with('success', 'Company updated successfully.');
            }
            return redirect()->route('company.index')->with('success', 'Company updated successfully.');
        } else {
            // Company owner flow
            return redirect()->route('my-company.show')->with('success', 'Company updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return redirect()->route('company.index')->with('success', 'Company archived successfully.');

    }

    public function restore($id)
    {
        $company = Company::withTrashed()->findOrFail($id);
        if ($company->trashed()) {
            $company->restore();
            return redirect()->route('company.index')->with('success', 'Company restored successfully.');
        }
        return redirect()->route('company.index',['archived' =>'true'])->with('info', 'Company is not archived.');
    }


}
