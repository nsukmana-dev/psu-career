<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdatePreferencesRequest;
use Illuminate\Http\RedirectResponse;

class PreferenceController extends Controller
{
    public function update(UpdatePreferencesRequest $request): RedirectResponse
    {
        $request->user()->student->update($request->validated());

        return back()->with('status', 'Preferensi kerja tersimpan.');
    }
}
