<?php

namespace App\Enums;

enum DamageLevel: string
{
    case None = 'none';
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Unknown = 'unknown';
}
