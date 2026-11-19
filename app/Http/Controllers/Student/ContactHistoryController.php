<?php

namespace App\Http\Controllers\Student;

use App\Enums\ItemStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $items = $request->user()->student->contactRequestItems()
            ->where('status', ItemStatus::Approved)
            ->with(['contactRequest.company'])
            ->orderByDesc('decided_at')
            ->get();

        return view('student.contact-history.index', ['items' => $items]);
    }
}
