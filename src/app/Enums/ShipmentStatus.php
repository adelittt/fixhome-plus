<?php

namespace App\Enums;

enum ShipmentStatus: string
{
    case BELUM_DIBUAT = 'belum_dibuat';
    case DIMINTA = 'diminta';
    case DIJEMPUT = 'dijemput';
    case DALAM_PERJALANAN = 'dalam_perjalanan';
    case TERKIRIM = 'terkirim';
    case GAGAL = 'gagal';
    case DIBATALKAN = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::BELUM_DIBUAT => 'Belum Dibuat',
            self::DIMINTA => 'Permintaan Penjemputan',
            self::DIJEMPUT => 'Kurir Menjemput Paket',
            self::DALAM_PERJALANAN => 'Dalam Perjalanan',
            self::TERKIRIM => 'Terkirim / Diterima',
            self::GAGAL => 'Pengiriman Gagal',
            self::DIBATALKAN => 'Pengiriman Dibatalkan',
        };
    }
}
