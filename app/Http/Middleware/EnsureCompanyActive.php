<?php

namespace App\Http\Middleware;

use App\Enums\CompanyStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $company = $request->user()?->company;

        if (! $company || $company->status !== CompanyStatus::Active) {
            abort(403, 'Akun perusahaan Anda sedang ditangguhkan.');
        }

        return $next($request);
    }
}
