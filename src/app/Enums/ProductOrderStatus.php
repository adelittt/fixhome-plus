<?php

namespace App\Enums;

enum ProductOrderStatus: string
{
    case MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    case DIBAYAR = 'dibayar';
    case DIPROSES = 'diproses';
    case SIAP_DIKIRIM = 'siap_dikirim';
    case DIKIRIM = 'dikirim';
    case SELESAI = 'selesai';
    case DITOLAK_TOKO = 'ditolak_toko';
    case DIBATALKAN = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU_PEMBAYARAN => 'Menunggu Pembayaran',
            self::DIBAYAR => 'Sudah Dibayar',
            self::DIPROSES => 'Diproses Toko',
            self::SIAP_DIKIRIM => 'Siap Dikirim',
            self::DIKIRIM => 'Sedang Dikirim',
            self::SELESAI => 'Selesai',
            self::DITOLAK_TOKO => 'Ditolak Toko',
            self::DIBATALKAN => 'Dibatalkan',
        };
    }
}
