<?php

namespace App\Enums;

enum ReportType: string
{
    case RUSAK = 'rusak';
    case HILANG = 'hilang';

    public function label(): string
    {
        return match ($this) {
            self::RUSAK => 'Barang Rusak',
            self::HILANG => 'Barang Hilang',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::RUSAK => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
            self::HILANG => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
        };
    }

    /** @return array<string,string> */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $c) => [$c->value => $c->label()])
            ->all();
    }
}
