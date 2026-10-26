<?php

namespace App\Enums;

enum ServiceOrderStatus: string
{
    case MENUNGGU_TEKNISI = 'menunggu_teknisi';
    case DITERIMA = 'diterima';
    case BERLANGSUNG = 'berlangsung';
    case SELESAI = 'selesai';
    case DITOLAK = 'ditolak';
    case DIBATALKAN = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU_TEKNISI => 'Menunggu Konfirmasi Teknisi',
            self::DITERIMA => 'Diterima Teknisi',
            self::BERLANGSUNG => 'Sedang Dikerjakan',
            self::SELESAI => 'Selesai',
            self::DITOLAK => 'Ditolak Teknisi',
            self::DIBATALKAN => 'Dibatalkan',
        };
    }
}
