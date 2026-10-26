<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        // PK-15: Pengaturan apakah biaya jarak rute masuk ke dasar komisi jasa 5%
        // Nilai DEFAULT: false (komisi jasa 5% dihitung murni dari tarif dasar jasa)
        SystemSetting::updateOrCreate(
            ['key' => 'service_commission_includes_distance_fee'],
            [
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Menentukan apakah biaya jarak rute masuk ke dalam dasar perhitungan komisi jasa 5% (PK-15). Default: false.',
            ]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'distance_fee_threshold_km'],
            [
                'value' => '10.0',
                'type' => 'float',
                'description' => 'Batas jarak rute minimum untuk pengenaan biaya tambahan jarak teknisi (AB-002). Default: 10.0 km.',
            ]
        );

        SystemSetting::updateOrCreate(
            ['key' => 'distance_fee_amount'],
            [
                'value' => '10000',
                'type' => 'integer',
                'description' => 'Besaran biaya tambahan jarak dalam Rupiah (AB-002). Default: Rp10.000.',
            ]
        );
    }
}
