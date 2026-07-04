<?php

namespace App\Enums;

enum ProcurementStatus: string
{
    case MENUNGGU = 'menunggu';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';
    case SUDAH_DIBELI = 'sudah_dibeli';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU => 'Menunggu',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::SUDAH_DIBELI => 'Sudah Dibeli',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::MENUNGGU => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
            self::DISETUJUI => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
            self::DITOLAK => 'bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300',
            self::SUDAH_DIBELI => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
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
