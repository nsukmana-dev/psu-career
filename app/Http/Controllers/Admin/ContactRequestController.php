<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ItemStatus;
use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectContactRequestItemRequest;
use App\Models\ContactRequest;
use App\Models\ContactRequestItem;
use App\Services\ContactRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class ContactRequestController extends Controller
{
    public function index(Request $request): View
    {
        $sortable = ['date' => 'created_at', 'position' => 'position_title', 'status' => 'status'];
        $sort = $sortable[$request->get('sort')] ?? 'created_at';
        $dir = $request->get('dir') === 'asc' ? 'asc' : 'desc';

        $requests = ContactRequest::query()
            ->with(['company', 'items.student.user', 'items.student.studyProgram'])
            ->withCount([
                'items as pending_count' => fn ($q) => $q->where('status', ItemStatus::Pending),
                'items as approved_count' => fn ($q) => $q->where('status', ItemStatus::Approved),
            ])
            ->when($request->status && $request->status !== 'all', fn ($q) => $q->where('status', $request->status))
            ->when($request->type && $request->type !== 'all', fn ($q) => $q->where('employment_type', $request->type))
            ->when($request->search, fn ($q, $term) => $q->where(fn ($q2) => $q2
                ->where('request_number', 'like', "%{$term}%")
                ->orWhere('position_title', 'like', "%{$term}%")
                ->orWhereHas('company', fn ($q3) => $q3->where('name', 'like', "%{$term}%"))
            ))
            ->orderBy($sort, $dir)
            ->paginate(10)
            ->withQueryString();

        $thisMonth = now()->month;
        $thisYear = now()->year;

        return view('admin.contact-requests.index', [
            'requests' => $requests,
            'stats' => [
                'total_month' => ContactRequest::whereMonth('created_at', $thisMonth)->whereYear('created_at', $thisYear)->count(),
                'pending' => ContactRequest::where('status', RequestStatus::Pending)->count(),
                'completed_month' => ContactRequest::where('status', RequestStatus::Completed)
                    ->whereMonth('updated_at', $thisMonth)->whereYear('updated_at', $thisYear)->count(),
                'shared_month' => ContactRequestItem::where('status', ItemStatus::Approved)
                    ->whereMonth('decided_at', $thisMonth)->whereYear('decided_at', $thisYear)->count(),
            ],
        ]);
    }

    public function show(ContactRequest $contactRequest): View
    {
        $contactRequest->load(['company', 'items.student.user', 'items.student.studyProgram']);

        return view('admin.contact-requests.show', ['contactRequest' => $contactRequest]);
    }

    public function approveItem(ContactRequestItem $item, Request $request, ContactRequestService $service): RedirectResponse
    {
        try {
            $service->decideItem($item, $request->user(), ItemStatus::Approved);
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('status', 'Item disetujui.');
    }

    public function rejectItem(ContactRequestItem $item, RejectContactRequestItemRequest $request, ContactRequestService $service): RedirectResponse
    {
        try {
            $service->decideItem($item, $request->user(), ItemStatus::Rejected, $request->rejection_reason);
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('status', 'Item ditolak.');
    }

    public function approveAll(ContactRequest $contactRequest, Request $request, ContactRequestService $service): RedirectResponse
    {
        try {
            $service->decideAll($contactRequest, $request->user(), ItemStatus::Approved);
        } catch (InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.contact-requests.index')->with('status', 'Semua item disetujui.');
    }
}
