<?php

namespace App\Enums;

enum EmploymentType: string
{
    case Internship = 'internship';
    case Fulltime = 'fulltime';

    public function label(): string
    {
        return match ($this) {
            self::Internship => 'Magang',
            self::Fulltime => 'Full-time',
        };
    }
}
