<?php

namespace App\Support;

class VercelFeatures
{
    public static function hasExcel(): bool
    {
        return class_exists(\Maatwebsite\Excel\Facades\Excel::class);
    }

    public static function hasPdf(): bool
    {
        return class_exists(\Barryvdh\DomPDF\Facade\Pdf::class);
    }

    public static function hasQrCode(): bool
    {
        return class_exists(\SimpleSoftwareIO\QrCode\Facades\QrCode::class);
    }
}
