<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // إنشاء المستخدم الإداري الرئيسي
        User::firstOrCreate([
            "email" => "admin@admin.com"
        ], [
            "name" => "Admin",
            "password" => Hash::make("12345678"),
            "role" => "admin",
            "email_verified_at" => now(),
        ]);

        // قراءة بيانات الوظائف والشركات
        $jobData = json_decode(file_get_contents(database_path('data/job_data.json')), true);

        // إنشاء تصنيفات الوظائف
        foreach ($jobData["jobCategories"] as $category) {
            JobCategory::firstOrCreate([
                "name" => $category,
            ]);
        }

        // إنشاء الشركات ومالكيها
        foreach ($jobData["companies"] as $company) {
            // إنشاء مالك الشركة - sabit şifre ile
            $companyOwner = User::firstOrCreate([
                "email" => fake()->unique()->safeEmail()
            ], [
                "name" => fake()->name(),
                "password" => Hash::make("12345678"), // Sabit test şifresi
                "role" => "company-owner",
                "email_verified_at" => now(),
            ]);

            Company::firstOrCreate([
                "name" => $company["name"],
            ], [
                "address" => $company["address"],
                "industry" => $company["industry"],
                "website" => $company["website"],
                "ownerid" => $companyOwner->id,
            ]);
        }

        // إنشاء الوظائف الشاغرة
        foreach ($jobData["jobVacancies"] as $job) {
            $company = Company::where("name", $job["company"])->firstOrFail();
            $jobCategory = JobCategory::where("name", $job["category"])->firstOrFail();

            JobVacancy::firstOrCreate([
                "title" => $job["title"],
                "company_id" => $company->id,
            ], [
                "description" => $job["description"],
                "location" => $job["location"],
                "type" => $job["type"],
                "salary" => $job["salary"],
                "jobcategory_id" => $jobCategory->id,
            ]);
        }

        // قراءة بيانات Job Applications من الملف الجديد
        $jobApplicationsData = json_decode(file_get_contents(database_path('data/job_applications.json')), true);

        // إنشاء Job Applications
        if (isset($jobApplicationsData["jobApplications"])) {
            foreach ($jobApplicationsData["jobApplications"] as $application) {
                // اختيار وظيفة عشوائية لربط التطبيق بها
                $jobVacancy = JobVacancy::inRandomOrder()->first();

                // إنشاء المستخدم المتقدم (Job Seeker) - sabit şifre ile
                $applicant = User::firstOrCreate([
                    "email" => fake()->unique()->safeEmail()
                ], [
                    "name" => fake()->name(),
                    "password" => Hash::make("12345678"), // Sabit test şifresi
                    "role" => "job-seeker",
                    "email_verified_at" => now(),
                ]);

                // إنشاء السيرة الذاتية
                $resume = Resume::create([
                    "userid" => $applicant->id,
                    "filename" => $application["resume"]["filename"] ?? null,
                    "fileurl" => $application["resume"]["fileUri"] ?? null,
                    "summary" => $application["resume"]["summary"] ?? null,
                    "contactDetails" => $application["resume"]["contactDetails"] ?? null,
                    "education" => $application["resume"]["education"] ?? null,
                    "experience" => $application["resume"]["experience"] ?? null,
                    "skills" => $application["resume"]["skills"] ?? null,
                ]);

                // إنشاء طلب التوظيف
                JobApplication::create([
                    "userid" => $applicant->id,
                    "jobvacancyid" => $jobVacancy->id,
                    "resumeid" => $resume->id,
                    "aigeneratedscore" => $application["aiGeneratedScore"] ?? 0,
                    "aigeneratedfeedback" => $application["aiGeneratedFeedback"] ?? null,
                ]);
            }
        }
    }
}
