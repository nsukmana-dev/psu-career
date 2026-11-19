<?php

namespace App\Models;

use App\Enums\LandingSettingType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['key', 'value', 'type'])]
class LandingSetting extends Model
{
    protected function casts(): array
    {
        return [
            'type' => LandingSettingType::class,
        ];
    }
}
