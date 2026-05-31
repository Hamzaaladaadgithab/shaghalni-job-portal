<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobVacancy;
use App\Models\JobApplication;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Role göre dashboard verilerini al
        if(Auth::user()->role == 'admin'){
            $dashboardData = $this->adminDashboards();
        } else if(Auth::user()->role == 'company-owner'){
            $dashboardData = $this->companyOwnerDashboards();
        } else {
            // Default dashboard for other roles
            $dashboardData = [
                'analytics' => [],
                'mostAppliedJobs' => [],
                'topConvertingJobs' => []
            ];
        }

        return view('dashboard.index', [
            'analytics' => $dashboardData['analytics'],
            'mostAppliedJobs' => $dashboardData['mostAppliedJobs'],
            'topConvertingJobs' => $dashboardData['topConvertingJobs']
        ]);
    }


    private function adminDashboards(){
        $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))
            ->whereNotNull('last_login_at')
            ->count();

        $totalJobs = JobVacancy::whereNull('deleted_at')->count();
        $totalApplications = JobApplication::whereNull('deleted_at')->count();

        // Analytics array
        $analytics = [
            'activeUsers' => $activeUsers,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications
        ];

        // En çok başvuru alan işler
        $mostAppliedJobs = JobVacancy::withCount('jobapplications')
            ->with('company')
            ->whereNull('deleted_at')
            ->orderBy('jobapplications_count', 'desc')
            ->take(5)
            ->get();

        // En yüksek dönüşüm oranına sahip işler
        $topConvertingJobs = JobVacancy::withCount([
            'jobapplications',
            'jobapplications as accepted_count' => function($query) {
                $query->where('status', 'accepted');
            }
        ])
        ->with('company')
        ->whereNull('deleted_at')
        ->having('jobapplications_count', '>', 0)
        ->take(5)
        ->get()
        ->map(function($job) {
            $acceptedCount = $job->accepted_count ?? 0;
            $totalApplications = $job->jobapplications_count ?? 0;

            if ($totalApplications > 0) {
                $job->conversion_rate = round(($acceptedCount / $totalApplications) * 100, 1);
            } else {
                $job->conversion_rate = 0;
            }

            return $job;
        })
        ->sortByDesc('conversion_rate');

        return [
            'analytics' => $analytics,
            'mostAppliedJobs' => $mostAppliedJobs,
            'topConvertingJobs' => $topConvertingJobs
        ];
    }




    private function companyOwnerDashboards(){
        // Company owner'ın şirketini bul
        $company = Company::where('ownerid', Auth::user()->id)->first();

        if (!$company) {
            return [
                'analytics' => [
                    'error' => 'No company found',
                    'activeUsers' => 0,
                    'totalJobs' => 0,
                    'totalApplications' => 0
                ],
                'mostAppliedJobs' => [],
                'topConvertingJobs' => []
            ];
        }

        // Sadece kendi şirketinin iş ilanları için istatistikler
        $totalJobs = JobVacancy::whereHas('company', function($q) use ($company) {
            $q->where('id', $company->id);
        })->whereNull('deleted_at')->count();

        $totalApplications = JobApplication::whereHas('jobvacancy.company', function($q) use ($company) {
            $q->where('id', $company->id);
        })->whereNull('deleted_at')->count();

        // Company-owner için activeUsers = kendi şirketine başvuran unique kullanıcı sayısı
        $activeUsers = JobApplication::whereHas('jobvacancy.company', function($q) use ($company) {
            $q->where('id', $company->id);
        })
        ->whereNull('deleted_at')
        ->distinct('userid')
        ->count('userid');

        $analytics = [
            'activeUsers' => $activeUsers, // Şirketine başvuran kullanıcı sayısı
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'companyName' => $company->name
        ];

        // Kendi şirketinin en çok başvuru alan işleri
        $mostAppliedJobs = JobVacancy::withCount('jobapplications')
            ->whereHas('company', function($q) use ($company) {
                $q->where('id', $company->id);
            })
            ->whereNull('deleted_at')
            ->orderBy('jobapplications_count', 'desc')
            ->take(5)
            ->get();

        // Kendi şirketinin en yüksek dönüşüm oranına sahip işleri
        $topConvertingJobs = JobVacancy::withCount([
            'jobapplications',
            'jobapplications as accepted_count' => function($query) {
                $query->where('status', 'accepted');
            }
        ])
        ->whereHas('company', function($q) use ($company) {
            $q->where('id', $company->id);
        })
        ->whereNull('deleted_at')
        ->having('jobapplications_count', '>', 0)
        ->take(5)
        ->get()
        ->map(function($job) {
            $acceptedCount = $job->accepted_count ?? 0;
            $totalApplications = $job->jobapplications_count ?? 0;

            if ($totalApplications > 0) {
                $job->conversion_rate = round(($acceptedCount / $totalApplications) * 100, 1);
            } else {
                $job->conversion_rate = 0;
            }

            // Debug bilgileri ekle (view bunları bekliyor)
            $job->debug_accepted = $acceptedCount;
            $job->debug_total = $totalApplications;

            return $job;
        })
        ->sortByDesc('conversion_rate');

        return [
            'analytics' => $analytics,
            'mostAppliedJobs' => $mostAppliedJobs,
            'topConvertingJobs' => $topConvertingJobs
        ];
    }
}




