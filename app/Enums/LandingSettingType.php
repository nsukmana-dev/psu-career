<?php

namespace App\Enums;

enum LandingSettingType: string
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Image = 'image';
    case Number = 'number';
}
