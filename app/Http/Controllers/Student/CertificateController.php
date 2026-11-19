<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreCertificateRequest;
use App\Models\Certificate;
use App\Services\ProfileCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CertificateController extends Controller
{
    public function store(StoreCertificateRequest $request, ProfileCompletionService $completion): RedirectResponse
    {
        $student = $request->user()->student;

        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('certificates/'.$student->id, 'local');
        }

        $student->certificates()->create($data);
        $completion->refresh($student);

        return back()->with('status', 'Sertifikat ditambahkan.');
    }

    public function destroy(Request $request, Certificate $certificate, ProfileCompletionService $completion): RedirectResponse
    {
        $this->authorize('delete', $certificate);

        if ($certificate->file_path) {
            Storage::disk('local')->delete($certificate->file_path);
        }

        $student = $certificate->student;
        $certificate->delete();
        $completion->refresh($student);

        return back()->with('status', 'Sertifikat dihapus.');
    }

    public function download(Certificate $certificate): StreamedResponse
    {
        $this->authorize('view', $certificate);

        abort_unless($certificate->file_path, 404);

        return Storage::disk('local')->download($certificate->file_path);
    }
}
