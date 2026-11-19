<?php

use App\Http\Controllers\Company\CandidateController;
use App\Http\Controllers\Company\ContactController;
use App\Http\Controllers\Company\ContactRequestController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\SavedSearchController;
use App\Http\Controllers\Company\ShortlistController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'password.changed', 'role:company', 'company.active'])
    ->prefix('company')
    ->name('company.')
    ->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
        Route::get('/candidates/{student}', [CandidateController::class, 'show'])->name('candidates.show');
        Route::get('/candidates/{student}/cv-preview', [CandidateController::class, 'cvPreview'])->name('candidates.cv-preview');

        Route::get('/shortlists', [ShortlistController::class, 'index'])->name('shortlists.index');
        Route::post('/shortlists', [ShortlistController::class, 'store'])->name('shortlists.store');
        Route::get('/shortlists/{shortlist}', [ShortlistController::class, 'show'])->name('shortlists.show');
        Route::delete('/shortlists/{shortlist}', [ShortlistController::class, 'destroy'])->name('shortlists.destroy');
        Route::post('/shortlists/{shortlist}/items', [ShortlistController::class, 'storeItem'])->name('shortlists.items.store');
        Route::post('/shortlist-items', [ShortlistController::class, 'quickAddItem'])->name('shortlists.items.quick-add');
        Route::delete('/shortlists/{shortlist}/items/{item}', [ShortlistController::class, 'destroyItem'])->name('shortlists.items.destroy');

        Route::post('/saved-searches', [SavedSearchController::class, 'store'])->name('saved-searches.store');
        Route::get('/saved-searches/{savedSearch}/apply', [SavedSearchController::class, 'apply'])->name('saved-searches.apply');
        Route::delete('/saved-searches/{savedSearch}', [SavedSearchController::class, 'destroy'])->name('saved-searches.destroy');

        Route::get('/contact-requests', [ContactRequestController::class, 'index'])->name('contact-requests.index');
        Route::get('/contact-requests/create', [ContactRequestController::class, 'create'])->name('contact-requests.create');
        Route::post('/contact-requests', [ContactRequestController::class, 'store'])->name('contact-requests.store');
        Route::get('/contact-requests/{contactRequest}', [ContactRequestController::class, 'show'])->name('contact-requests.show');

        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
    });
