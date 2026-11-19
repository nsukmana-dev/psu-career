<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ContactRequestController;
use App\Http\Controllers\Admin\CvTemplateController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\IndustryController;
use App\Http\Controllers\Admin\LandingSettingController;
use App\Http\Controllers\Admin\PartnershipApplicationController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentUpdateRequestController;
use App\Http\Controllers\Admin\StudyProgramController;
use App\Http\Controllers\Admin\VerificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'password.changed', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('/verifications', [VerificationController::class, 'index'])->name('verifications.index');
        Route::get('/verifications/{student}', [VerificationController::class, 'show'])->name('verifications.show');
        Route::post('/verifications/{student}/approve', [VerificationController::class, 'approve'])->name('verifications.approve');
        Route::post('/verifications/{student}/reject', [VerificationController::class, 'reject'])->name('verifications.reject');

        Route::get('/update-requests', [StudentUpdateRequestController::class, 'index'])->name('update-requests.index');
        Route::get('/update-requests/{updateRequest}', [StudentUpdateRequestController::class, 'show'])->name('update-requests.show');
        Route::post('/update-requests/{updateRequest}/approve', [StudentUpdateRequestController::class, 'approve'])->name('update-requests.approve');
        Route::post('/update-requests/{updateRequest}/reject', [StudentUpdateRequestController::class, 'reject'])->name('update-requests.reject');

        Route::get('/partnership-applications', [PartnershipApplicationController::class, 'index'])->name('partnership-applications.index');
        Route::get('/partnership-applications/{application}', [PartnershipApplicationController::class, 'show'])->name('partnership-applications.show');
        Route::post('/partnership-applications/{application}/approve', [PartnershipApplicationController::class, 'approve'])->name('partnership-applications.approve');
        Route::post('/partnership-applications/{application}/reject', [PartnershipApplicationController::class, 'reject'])->name('partnership-applications.reject');

        Route::get('/companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
        Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
        Route::post('/companies/{company}/suspend', [CompanyController::class, 'suspend'])->name('companies.suspend');
        Route::post('/companies/{company}/activate', [CompanyController::class, 'activate'])->name('companies.activate');
        Route::post('/companies/{company}/reset-password', [CompanyController::class, 'resetPassword'])->name('companies.reset-password');

        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::post('/students/{student}/toggle-active', [StudentController::class, 'toggleActive'])->name('students.toggle-active');
        Route::post('/students/{student}/reset-password', [StudentController::class, 'resetPassword'])->name('students.reset-password');

        Route::get('/study-programs', [StudyProgramController::class, 'index'])->name('study-programs.index');
        Route::post('/study-programs', [StudyProgramController::class, 'store'])->name('study-programs.store');
        Route::put('/study-programs/{studyProgram}', [StudyProgramController::class, 'update'])->name('study-programs.update');
        Route::post('/study-programs/{studyProgram}/toggle-active', [StudyProgramController::class, 'toggleActive'])->name('study-programs.toggle-active');

        Route::get('/skills', [SkillController::class, 'index'])->name('skills.index');
        Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
        Route::put('/skills/{skill}', [SkillController::class, 'update'])->name('skills.update');
        Route::post('/skills/{skill}/approve', [SkillController::class, 'approve'])->name('skills.approve');
        Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');

        Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
        Route::post('/industries', [IndustryController::class, 'store'])->name('industries.store');
        Route::put('/industries/{industry}', [IndustryController::class, 'update'])->name('industries.update');
        Route::delete('/industries/{industry}', [IndustryController::class, 'destroy'])->name('industries.destroy');

        Route::get('/cv-templates', [CvTemplateController::class, 'index'])->name('cv-templates.index');
        Route::put('/cv-templates/{cvTemplate}', [CvTemplateController::class, 'update'])->name('cv-templates.update');
        Route::post('/cv-templates/{cvTemplate}/toggle-active', [CvTemplateController::class, 'toggleActive'])->name('cv-templates.toggle-active');

        Route::get('/contact-requests', [ContactRequestController::class, 'index'])->name('contact-requests.index');
        Route::get('/contact-requests/{contactRequest}', [ContactRequestController::class, 'show'])->name('contact-requests.show');
        Route::post('/contact-requests/{contactRequest}/approve-all', [ContactRequestController::class, 'approveAll'])->name('contact-requests.approve-all');
        Route::post('/contact-request-items/{item}/approve', [ContactRequestController::class, 'approveItem'])->name('contact-request-items.approve');
        Route::post('/contact-request-items/{item}/reject', [ContactRequestController::class, 'rejectItem'])->name('contact-request-items.reject');

        Route::get('/landing-settings', [LandingSettingController::class, 'edit'])->name('landing-settings.edit');
        Route::put('/landing-settings', [LandingSettingController::class, 'update'])->name('landing-settings.update');

        Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
        Route::post('/faqs', [FaqController::class, 'store'])->name('faqs.store');
        Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
        Route::post('/faqs/{faq}/toggle', [FaqController::class, 'toggle'])->name('faqs.toggle');
        Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

        Route::get('/export/students', [ExportController::class, 'students'])->name('export.students');
        Route::get('/export/contact-requests', [ExportController::class, 'contactRequests'])->name('export.contact-requests');
    });
