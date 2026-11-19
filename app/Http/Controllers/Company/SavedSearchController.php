<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreSavedSearchRequest;
use App\Models\SavedSearch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SavedSearchController extends Controller
{
    public function store(StoreSavedSearchRequest $request): RedirectResponse
    {
        $request->user()->company->savedSearches()->create($request->validated());

        return back()->with('status', 'Filter pencarian disimpan.');
    }

    public function destroy(Request $request, SavedSearch $savedSearch): RedirectResponse
    {
        abort_unless($savedSearch->company_id === $request->user()->company->id, 403);

        $savedSearch->delete();

        return back()->with('status', 'Filter pencarian dihapus.');
    }

    public function apply(Request $request, SavedSearch $savedSearch): RedirectResponse
    {
        abort_unless($savedSearch->company_id === $request->user()->company->id, 403);

        return redirect()->route('company.candidates.index', $savedSearch->filters);
    }
}
