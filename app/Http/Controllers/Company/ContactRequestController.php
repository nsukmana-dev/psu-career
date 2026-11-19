<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\StoreContactRequestRequest;
use App\Models\ContactRequest;
use App\Models\Student;
use App\Services\ContactRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class ContactRequestController extends Controller
{
    public function index(Request $request): View
    {
        $requests = $request->user()->company->contactRequests()
            ->withCount('items')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('company.contact-requests.index', ['requests' => $requests]);
    }

    public function create(Request $request): View
    {
        $studentIds = array_filter((array) $request->query('student_ids', []));

        return view('company.contact-requests.create', [
            'students' => Student::with('user')->whereIn('id', $studentIds)->get(),
        ]);
    }

    public function store(StoreContactRequestRequest $request, ContactRequestService $service): RedirectResponse
    {
        try {
            $contactRequest = $service->create(
                $request->user()->company,
                $request->only(['position_title', 'employment_type', 'work_location', 'message']),
                $request->student_ids
            );
        } catch (InvalidArgumentException $e) {
            return back()->withInput()->withErrors(['student_ids' => $e->getMessage()]);
        }

        return redirect()->route('company.contact-requests.show', $contactRequest)
            ->with('status', 'Request kontak terkirim.');
    }

    public function show(Request $request, ContactRequest $contactRequest): View
    {
        abort_unless($contactRequest->company_id === $request->user()->company->id, 403);

        $contactRequest->load(['items.student.user', 'items.student.studyProgram']);

        return view('company.contact-requests.show', ['contactRequest' => $contactRequest]);
    }
}
