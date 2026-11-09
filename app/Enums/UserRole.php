<?php

namespace App\Enums;

enum UserRole: string
{
    case Guru = 'guru';
    case Siswa = 'siswa';

    /** @return array<string, string> */
    public static function options(): array
    {
        return [
            self::Guru->value => 'Guru',
            self::Siswa->value => 'Siswa',
        ];
    }

    public function label(): string
    {
        return match ($this) {
            self::Guru => 'Guru',
            self::Siswa => 'Siswa',
        };
    }
}

