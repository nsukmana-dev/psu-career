<?php

namespace App\Enums;

enum SkillLevel: string
{
    case Basic = 'basic';
    case Intermediate = 'intermediate';
    case Advanced = 'advanced';

    public function label(): string
    {
        return match ($this) {
            self::Basic => 'Dasar',
            self::Intermediate => 'Menengah',
            self::Advanced => 'Mahir',
        };
    }
}
