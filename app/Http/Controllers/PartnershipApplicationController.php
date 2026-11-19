<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartnershipApplicationRequest;
use App\Models\PartnershipApplication;
use Illuminate\Http\RedirectResponse;

class PartnershipApplicationController extends Controller
{
    public function store(StorePartnershipApplicationRequest $request): RedirectResponse
    {
        PartnershipApplication::create($request->safe()->except('website'));

        return back()->with('status', 'Pengajuan kemitraan terkirim. Tim kami akan meninjau dan menghubungi Anda.');
    }
}
