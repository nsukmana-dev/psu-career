<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Certificate;
use App\Models\User;

class CertificatePolicy
{
    public function view(User $user, Certificate $certificate): bool
    {
        return $user->role === UserRole::Admin || $user->id === $certificate->student->user_id;
    }

    public function delete(User $user, Certificate $certificate): bool
    {
        return $user->id === $certificate->student->user_id;
    }
}
