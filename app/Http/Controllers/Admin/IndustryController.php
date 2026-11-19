<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreIndustryRequest;
use App\Models\Industry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class IndustryController extends Controller
{
    public function index(): View
    {
        return view('admin.industries.index', [
            'industries' => Industry::withCount('companies')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreIndustryRequest $request): RedirectResponse
    {
        Industry::create($request->validated());

        return back()->with('status', 'Industri ditambahkan.');
    }

    public function update(StoreIndustryRequest $request, Industry $industry): RedirectResponse
    {
        $industry->update($request->validated());

        return back()->with('status', 'Industri diperbarui.');
    }

    public function destroy(Industry $industry): RedirectResponse
    {
        $industry->delete();

        return back()->with('status', 'Industri dihapus.');
    }
}
