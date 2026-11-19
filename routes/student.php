<?php

use App\Http\Controllers\Student\CertificateController;
use App\Http\Controllers\Student\ContactHistoryController;
use App\Http\Controllers\Student\CvController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\EducationController;
use App\Http\Controllers\Student\ExperienceController;
use App\Http\Controllers\Student\PortfolioController;
use App\Http\Controllers\Student\PreferenceController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\SkillController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'password.changed', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'updateBasic'])->name('profile.update');
        Route::post('/profile/submit', [ProfileController::class, 'submit'])->name('profile.submit');

        Route::post('/skills', [SkillController::class, 'store'])->name('skills.store');
        Route::delete('/skills/{skill}', [SkillController::class, 'destroy'])->name('skills.destroy');

        Route::post('/experiences', [ExperienceController::class, 'store'])->name('experiences.store');
        Route::delete('/experiences/{experience}', [ExperienceController::class, 'destroy'])->name('experiences.destroy');

        Route::post('/educations', [EducationController::class, 'store'])->name('educations.store');
        Route::delete('/educations/{education}', [EducationController::class, 'destroy'])->name('educations.destroy');

        Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
        Route::delete('/certificates/{certificate}', [CertificateController::class, 'destroy'])->name('certificates.destroy');

        Route::post('/portfolios', [PortfolioController::class, 'store'])->name('portfolios.store');
        Route::delete('/portfolios/{portfolio}', [PortfolioController::class, 'destroy'])->name('portfolios.destroy');

        Route::put('/preferences', [PreferenceController::class, 'update'])->name('preferences.update');

        Route::get('/cv', [CvController::class, 'index'])->name('cv.index');
        Route::get('/cv/preview/{template:slug}', [CvController::class, 'preview'])->name('cv.preview');
        Route::get('/cv/download/{template:slug}', [CvController::class, 'download'])->name('cv.download');
        Route::put('/cv/settings', [CvController::class, 'updateSettings'])->name('cv.settings');

        Route::get('/contact-history', [ContactHistoryController::class, 'index'])->name('contact-history.index');
    });
