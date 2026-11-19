<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\QuickAddShortlistItemRequest;
use App\Http\Requests\Company\StoreShortlistItemRequest;
use App\Http\Requests\Company\StoreShortlistRequest;
use App\Models\Shortlist;
use App\Models\ShortlistItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShortlistController extends Controller
{
    public function index(Request $request): View
    {
        $shortlists = $request->user()->company->shortlists()->withCount('items')->orderByDesc('created_at')->get();

        return view('company.shortlists.index', ['shortlists' => $shortlists]);
    }

    public function store(StoreShortlistRequest $request): RedirectResponse
    {
        $shortlist = $request->user()->company->shortlists()->create($request->validated());

        return redirect()->route('company.shortlists.show', $shortlist)->with('status', 'Shortlist dibuat.');
    }

    public function show(Request $request, Shortlist $shortlist): View
    {
        abort_unless($shortlist->company_id === $request->user()->company->id, 403);

        $shortlist->load(['items.student.user', 'items.student.studyProgram']);

        return view('company.shortlists.show', ['shortlist' => $shortlist]);
    }

    public function destroy(Request $request, Shortlist $shortlist): RedirectResponse
    {
        abort_unless($shortlist->company_id === $request->user()->company->id, 403);

        $shortlist->delete();

        return redirect()->route('company.shortlists.index')->with('status', 'Shortlist dihapus.');
    }

    public function storeItem(StoreShortlistItemRequest $request, Shortlist $shortlist): RedirectResponse
    {
        abort_unless($shortlist->company_id === $request->user()->company->id, 403);

        ShortlistItem::query()->updateOrCreate(
            ['shortlist_id' => $shortlist->id, 'student_id' => $request->student_id],
            ['note' => $request->note]
        );

        return back()->with('status', 'Kandidat ditambahkan ke shortlist.');
    }

    public function quickAddItem(QuickAddShortlistItemRequest $request): RedirectResponse
    {
        $shortlist = Shortlist::findOrFail($request->shortlist_id);
        abort_unless($shortlist->company_id === $request->user()->company->id, 403);

        ShortlistItem::query()->updateOrCreate(
            ['shortlist_id' => $shortlist->id, 'student_id' => $request->student_id],
            ['note' => $request->note]
        );

        return back()->with('status', "Kandidat ditambahkan ke shortlist \"{$shortlist->name}\".");
    }

    public function destroyItem(Request $request, Shortlist $shortlist, ShortlistItem $item): RedirectResponse
    {
        abort_unless($shortlist->company_id === $request->user()->company->id, 403);
        abort_unless($item->shortlist_id === $shortlist->id, 404);

        $item->delete();

        return back()->with('status', 'Kandidat dihapus dari shortlist.');
    }
}
