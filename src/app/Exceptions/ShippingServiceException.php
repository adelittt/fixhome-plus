<?php

namespace App\Exceptions;

use Exception;

class ShippingServiceException extends Exception
{
    public static function adapterFailed(string $provider, string $reason): self
    {
        return new self("Layanan pengiriman {$provider} gagal merespons: {$reason}. Alur pesanan jasa perbaikan rumah tetap berjalan normal tanpa terganggu.");
    }

    public static function orderAlreadyDispatched(string $orderNumber): self
    {
        return new self("Pengiriman untuk pesanan #{$orderNumber} telah berjalan dan tidak dapat dibatalkan secara otomatis.");
    }

    public static function storeAddressNotFound(string $storeName): self
    {
        return new self("Alamat penjemputan untuk toko \"{$storeName}\" belum lengkap untuk kurir instan.");
    }
}
