<?php

namespace App\Enums;

enum ExperienceType: string
{
    case Work = 'work';
    case Internship = 'internship';
    case Organization = 'organization';
    case Committee = 'committee';
    case Volunteer = 'volunteer';

    public function label(): string
    {
        return match ($this) {
            self::Work => 'Kerja',
            self::Internship => 'Magang',
            self::Organization => 'Organisasi',
            self::Committee => 'Kepanitiaan',
            self::Volunteer => 'Volunteer',
        };
    }
}
