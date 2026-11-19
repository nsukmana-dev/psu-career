<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class Industry extends Model
{
    use HasFactory;

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }

    public function partnershipApplications(): HasMany
    {
        return $this->hasMany(PartnershipApplication::class);
    }
}
