<?php

namespace App\Services;

use App\Enums\CompanyStatus;
use App\Enums\UserRole;
use App\Models\Company;
use App\Models\PartnershipApplication;
use App\Models\User;
use App\Notifications\CompanyInvitation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CompanyOnboardingService
{
    public function createFromApplication(PartnershipApplication $application): Company
    {
        return $this->invite([
            'name' => $application->company_name,
            'industry_id' => $application->industry_id,
            'pic_name' => $application->pic_name,
            'pic_position' => $application->pic_position,
            'pic_phone' => $application->phone,
        ], $application->email);
    }

    public function createManually(array $companyAttrs, string $email): Company
    {
        return $this->invite($companyAttrs, $email);
    }

    private function invite(array $companyAttrs, string $email): Company
    {
        return DB::transaction(function () use ($companyAttrs, $email) {
            $user = User::create([
                'name' => $companyAttrs['pic_name'],
                'email' => $email,
                'password' => Hash::make(Str::password(32)),
                'role' => UserRole::Company,
                'must_change_password' => true,
                'email_verified_at' => now(),
            ]);

            $company = Company::create([
                ...$companyAttrs,
                'user_id' => $user->id,
                'status' => CompanyStatus::Active,
            ]);

            $token = Password::createToken($user);
            $user->notify(new CompanyInvitation($token));

            return $company;
        });
    }
}
