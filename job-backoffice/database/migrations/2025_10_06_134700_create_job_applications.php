<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->enum('status', ['pending', 'reviewed', 'accepted', 'rejected'])->default('pending');
            $table->float('aigeneratedscore' , 2)->default(0);
            $table->longText('aigeneratedfeedback')->nullable();
            $table->timestamps();
            $table->softDeletes();


            // relationship to users table as applicant
            $table->uuid('userid');
            $table->foreign('userid')->references('id')->on('users')->onDelete('restrict');

            // relationship to job_vacancies table
            $table->uuid('jobvacancyid');
            $table->foreign('jobvacancyid')->references('id')->on('job_vacancies')->onDelete('restrict');

            // relationship to resumes table
            $table->uuid('resumeid');
            $table->foreign('resumeid')->references('id')->on('resumes')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
            
        }

};
