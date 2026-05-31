<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Company;
use Illuminate\Support\Facades\Route;

// shared routes
Route::middleware(['auth','role:admin,company-owner'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    // İş başvuruları yönetimi - admin tümünü, company-owner sadece kendi şirketinin başvurularını görebilir
    Route::resource('job-application', JobApplicationController::class);
    Route::put('job-application/{id}/restore', [JobApplicationController::class, 'restore'])->name('job-application.restore');


    // İş ilanları yönetimi - admin tümünü, company-owner sadece kendi şirketinin ilanlarını görebilir
    Route::resource('job-vacancy', JobVacancyController::class);
    Route::put('job-vacancy/{id}/restore', [JobVacancyController::class, 'restore'])->name('job-vacancy.restore');


});




// COMPANY OWNER ERİŞİMİ
Route::middleware(['auth', 'role:company-owner'])->group(function () {
    Route::get('/my-company', [CompanyController::class,'show'])->name('my-company.show');
    Route::get('/my-company/edit', [CompanyController::class,'edit'])->name('my-company.edit');
    Route::put('/my-company', [CompanyController::class,'update'])->name('my-company.update');
});



// SADECE ADMİN ERİŞİMİ - Sistem yönetimi
Route::middleware(['auth', 'role:admin'])->group(function () {

    // İş kategorileri yönetimi - sadece admin
    Route::resource('job-category', JobCategoryController::class);
    Route::put('job-category/{id}/restore', [JobCategoryController::class, 'restore'])->name('job-category.restore');


    // Kullanıcı yönetimi - sadece admin
    Route::resource('user', UserController::class);
    Route::put('user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');



    // Şirket yönetimi - admin tümünü, company-owner sadece kendi şirketini görebilir
    Route::resource('company', CompanyController::class);
    Route::put('company/{id}/restore', [CompanyController::class, 'restore'])->name('company.restore');
});




require __DIR__.'/auth.php';
