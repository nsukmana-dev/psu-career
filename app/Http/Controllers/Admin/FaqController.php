<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFaqRequest;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        return view('admin.faqs.index', [
            'faqs' => Faq::orderBy('audience')->orderBy('sort_order')->get()->groupBy('audience'),
        ]);
    }

    public function store(StoreFaqRequest $request): RedirectResponse
    {
        Faq::create([...$request->validated(), 'is_active' => true]);

        return back()->with('status', 'FAQ ditambahkan.');
    }

    public function update(StoreFaqRequest $request, Faq $faq): RedirectResponse
    {
        $faq->update($request->validated());

        return back()->with('status', 'FAQ diperbarui.');
    }

    public function toggle(Faq $faq): RedirectResponse
    {
        $faq->update(['is_active' => ! $faq->is_active]);

        return back()->with('status', 'Status FAQ diperbarui.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('status', 'FAQ dihapus.');
    }
}
