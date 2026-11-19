<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CompanyStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCompanyRequest;
use App\Http\Requests\Admin\UpdateCompanyRequest;
use App\Models\ActivityLog;
use App\Models\Company;
use App\Models\Industry;
use App\Services\CompanyOnboardingService;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $companies = Company::query()
            ->with('industry')
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->search, fn ($q, $term) => $q->where('name', 'like', "%{$term}%"))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.companies.index', [
            'companies' => $companies,
            'industries' => Industry::orderBy('name')->get(),
            'activeCount' => Company::where('status', CompanyStatus::Active)->count(),
            'suspendedCount' => Company::where('status', CompanyStatus::Suspended)->count(),
        ]);
    }

    public function store(StoreCompanyRequest $request, CompanyOnboardingService $onboarding, ImageUploadService $images): RedirectResponse
    {
        $data = $request->safe()->except(['email', 'logo']);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $images->storeResized($request->file('logo'), 'public', 'companies', 300, 300);
        }

        $company = $onboarding->createManually($data, $request->email);

        return redirect()->route('admin.companies.index')
            ->with('status', "Perusahaan {$company->name} dibuat dan email undangan terkirim.");
    }

    public function update(UpdateCompanyRequest $request, Company $company, ImageUploadService $images): RedirectResponse
    {
        $data = $request->safe()->except('logo');

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $images->storeResized($request->file('logo'), 'public', 'companies', 300, 300);
        }

        $company->update($data);

        ActivityLog::record('company.updated', $company);

        return redirect()->route('admin.companies.index')->with('status', 'Data perusahaan diperbarui.');
    }

    public function suspend(Company $company): RedirectResponse
    {
        $company->update(['status' => CompanyStatus::Suspended]);

        ActivityLog::record('company.suspended', $company);

        return back()->with('status', 'Perusahaan ditangguhkan.');
    }

    public function activate(Company $company): RedirectResponse
    {
        $company->update(['status' => CompanyStatus::Active]);

        ActivityLog::record('company.activated', $company);

        return back()->with('status', 'Perusahaan diaktifkan kembali.');
    }

    public function resetPassword(Company $company): RedirectResponse
    {
        Password::sendResetLink(['email' => $company->user->email]);
        ActivityLog::record('company.password-reset-sent', $company);

        return back()->with('status', 'Email atur ulang password terkirim ke perusahaan.');
    }
}
