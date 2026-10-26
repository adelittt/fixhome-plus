<?php

namespace App\Enums;

enum MarketplaceChoice: string
{
    case BELUM_DIPILIH = 'belum_dipilih';
    case LIHAT = 'lihat';
    case LEWATI = 'lewati';

    public function label(): string
    {
        return match ($this) {
            self::BELUM_DIPILIH => 'Belum Dipilih',
            self::LIHAT => 'Lihat Marketplace',
            self::LEWATI => 'Lewati Marketplace',
        };
    }
}
