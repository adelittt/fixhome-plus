<?php

namespace App\Enums;

enum VerificationStatus: string
{
    case MENUNGGU = 'menunggu';
    case DISETUJUI = 'disetujui';
    case DITOLAK = 'ditolak';
    case DITANGGUHKAN = 'ditangguhkan';

    public function label(): string
    {
        return match ($this) {
            self::MENUNGGU => 'Menunggu Verifikasi',
            self::DISETUJUI => 'Disetujui',
            self::DITOLAK => 'Ditolak',
            self::DITANGGUHKAN => 'Ditangguhkan',
        };
    }
}
