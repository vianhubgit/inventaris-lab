<?php

namespace App\Enums;

enum ItemStatus: string
{
    case BAIK = 'baik';
    case RUSAK = 'rusak';
    case HILANG = 'hilang';
    case PERBAIKAN = 'perbaikan';

    public function label(): string
    {
        return match ($this) {
            self::BAIK => 'Baik',
            self::RUSAK => 'Rusak',
            self::HILANG => 'Hilang',
            self::PERBAIKAN => 'Dalam Perbaikan',
        };
    }

    /** Kelas badge Tailwind untuk tiap status. */
    public function badge(): string
    {
        return match ($this) {
            self::BAIK => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
            self::RUSAK => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
            self::HILANG => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
            self::PERBAIKAN => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
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
