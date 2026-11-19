<?php

namespace App\Http\Controllers\Company;

use App\Enums\ItemStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactRequestItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $company = $request->user()->company;

        $contacts = ContactRequestItem::query()
            ->where('status', ItemStatus::Approved)
            ->whereHas('contactRequest', fn ($q) => $q->where('company_id', $company->id))
            ->with(['student.user', 'student.studyProgram', 'contactRequest'])
            ->orderByDesc('decided_at')
            ->paginate(20);

        ActivityLog::record('contact.accessed', $company, ['count' => $contacts->total()]);

        return view('company.contacts.index', ['contacts' => $contacts]);
    }
}
