<?php

namespace App\Enums;

enum RepairStatus: string
{
    case PROSES = 'proses';
    case SELESAI = 'selesai';
    case GAGAL = 'gagal';

    public function label(): string
    {
        return match ($this) {
            self::PROSES => 'Sedang Diperbaiki',
            self::SELESAI => 'Selesai Diperbaiki',
            self::GAGAL => 'Gagal / Tidak Bisa Diperbaiki',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::PROSES => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
            self::SELESAI => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
            self::GAGAL => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
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
