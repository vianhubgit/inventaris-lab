<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case SEKRETARIS = 'sekretaris';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::SEKRETARIS => 'Sekretaris',
        };
    }

    /** @return array<string,string> value => label */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $c) => [$c->value => $c->label()])
            ->all();
    }
}
