<?php

namespace App\Enums;

enum JobInterest: string
{
    case Internship = 'internship';
    case Fulltime = 'fulltime';
    case Both = 'both';

    public function label(): string
    {
        return match ($this) {
            self::Internship => 'Magang',
            self::Fulltime => 'Full-time',
            self::Both => 'Magang & Full-time',
        };
    }
}
