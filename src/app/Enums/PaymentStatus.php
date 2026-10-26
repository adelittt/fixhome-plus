<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case MENUNGGU = 'menunggu';
    case BERHASIL = 'berhasil';
    case GAGAL = 'gagal';
    case KEDALUWARSA = 'kedaluwarsa';
    case DIKEMBALIKAN_MANUAL = 'dikembalikan_manual';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU => 'Menunggu Pembayaran',
            self::BERHASIL => 'Pembayaran Berhasil',
            self::GAGAL => 'Pembayaran Gagal',
            self::KEDALUWARSA => 'Kedaluwarsa',
            self::DIKEMBALIKAN_MANUAL => 'Dikembalikan Manual',
        };
    }
}
