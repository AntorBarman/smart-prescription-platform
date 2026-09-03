<?php

namespace App\Enums;

enum PrescriptionStatus: string
{
    case DRAFT = "draft";
    case ISSUED = "issued";
    case PARTIALLY_FULFILLED = "partially_fulfilled";
    case FULFILLED = "fulfilled";
    case CANCELLED = "cancelled";
    case EXPIRED = "expired";

    public static function values(): array
    {
        return array_column(self::cases(), "value");
    }
}
