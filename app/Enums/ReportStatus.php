<?php

namespace App\Enums;

enum ReportStatus: string
{
    case DILAPORKAN = 'dilaporkan';
    case DIPROSES = 'diproses';
    case SELESAI = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::DILAPORKAN => 'Dilaporkan',
            self::DIPROSES => 'Diproses',
            self::SELESAI => 'Selesai',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::DILAPORKAN => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300',
            self::DIPROSES => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300',
            self::SELESAI => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300',
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
