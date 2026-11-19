<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StorePortfolioRequest;
use App\Models\Portfolio;
use App\Services\ImageUploadService;
use App\Services\ProfileCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortfolioController extends Controller
{
    public function store(
        StorePortfolioRequest $request,
        ProfileCompletionService $completion,
        ImageUploadService $images
    ): RedirectResponse {
        $student = $request->user()->student;

        $data = $request->safe()->except('thumbnail');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $images->storeResized(
                $request->file('thumbnail'), 'public', 'portfolios/'.$student->id, 600, 400
            );
        }

        $student->portfolios()->create($data);
        $completion->refresh($student);

        return back()->with('status', 'Portfolio ditambahkan.');
    }

    public function destroy(Request $request, Portfolio $portfolio, ProfileCompletionService $completion): RedirectResponse
    {
        abort_unless($portfolio->student_id === $request->user()->student->id, 403);

        if ($portfolio->thumbnail_path) {
            Storage::disk('public')->delete($portfolio->thumbnail_path);
        }

        $student = $portfolio->student;
        $portfolio->delete();
        $completion->refresh($student);

        return back()->with('status', 'Portfolio dihapus.');
    }
}
