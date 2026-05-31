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
        Schema::create('job_vacancies', function (Blueprint $table) {

            $table->uuid('id')->primary();
            $table->string('title');
            $table->mediumText('description');
            $table->string('location');
            $table->string('salary');

            $table->enum('type' , ['full-time','contract','remote','hybrid'])->default('full-time');
            $table->timestamps();
            $table->softDeletes();

            // relationship to companies table
            $table->uuid('company_id');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('restrict');

            // relationship to job_categories table
            $table->uuid('jobcategory_id');
            $table->foreign('jobcategory_id')->references('id')->on('job_categories')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_vacancies');
            //
        }

};
