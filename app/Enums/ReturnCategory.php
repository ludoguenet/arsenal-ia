<?php

namespace App\Enums;

enum ReturnCategory: string
{
    case DamagedOnArrival = 'damaged_on_arrival';
    case Defective = 'defective';
    case MissingParts = 'missing_parts';
    case NotAsExpected = 'not_as_expected';
    case Other = 'other';
}
