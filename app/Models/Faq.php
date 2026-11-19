<?php

namespace App\Models;

use App\Enums\FaqAudience;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['audience', 'question', 'answer', 'sort_order', 'is_active'])]
class Faq extends Model
{
    protected function casts(): array
    {
        return [
            'audience' => FaqAudience::class,
            'is_active' => 'boolean',
        ];
    }
}
