<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;
use App\Models\JobCategory;
use App\Models\Company;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = JobVacancy::with(['company', 'jobcategory']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('company', function($companyQuery) use ($search) {
                      $companyQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Job type filter
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->get('location')}%");
        }

        $jobs = $query->latest()->paginate(10);
        $jobTypes = JobVacancy::distinct()->pluck('type')->filter();
        $locations = JobVacancy::distinct()->pluck('location')->filter();

        return view("dashboard", compact('jobs', 'jobTypes', 'locations'));
    }
}
