<?php

namespace App\Enums;

enum UserRole: string
{
    case PELANGGAN = 'pelanggan';
    case TEKNISI = 'teknisi';
    case MITRA_TOKO = 'mitra_toko';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::PELANGGAN => 'Pelanggan',
            self::TEKNISI => 'Teknisi',
            self::MITRA_TOKO => 'Mitra Toko',
            self::ADMIN => 'Administrator',
        };
    }
}
