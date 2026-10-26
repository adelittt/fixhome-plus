<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\ServiceCategory;
use App\Models\TechnicianProfile;
use App\Models\TechnicianService;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TechnicianSeeder extends Seeder
{
    public function run(): void
    {
        $catPipa = ServiceCategory::where('slug', 'perbaikan-pipa')->first();
        $catListrik = ServiceCategory::where('slug', 'instalasi-listrik')->first();
        $catAc = ServiceCategory::where('slug', 'perawatan-ac')->first();

        // 1. Teknisi 1: Budi Santoso (Spesialis Pipa - Tangerang)
        $userBudi = User::firstOrCreate(
            ['email' => 'budi.teknisi@fixhome.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => UserRole::TEKNISI,
                'phone' => '081233445566',
                'status' => 'aktif',
            ]
        );
        $userBudi->syncRoles([UserRole::TEKNISI->value]);

        $profileBudi = TechnicianProfile::firstOrCreate(
            ['user_id' => $userBudi->id],
            [
                'service_category_id' => $catPipa?->id,
                'bio' => 'Teknisi sanitari dan pipa berpengalaman 8 tahun menangani instalasi rumah tinggal dan ruko.',
                'base_rate' => 75000,
                'verification_status' => VerificationStatus::DISETUJUI,
                'is_available' => true,
                'address' => 'Jl. Daan Mogot KM 21 No. 12, Tanah Tinggi, Kota Tangerang',
                'latitude' => -6.1702000,
                'longitude' => 106.6403000,
            ]
        );

        TechnicianService::firstOrCreate(
            ['technician_profile_id' => $profileBudi->id, 'name' => 'Perbaikan Pipa Bocor'],
            ['description' => 'Mendeteksi titik kebocoran pipa tanam dan menyambung pipa baru.', 'price' => 75000]
        );
        TechnicianService::firstOrCreate(
            ['technician_profile_id' => $profileBudi->id, 'name' => 'Instalasi Saluran Wastafel / Kran Baru'],
            ['description' => 'Pemasangan unit kran air, saringan afur wastafel, dan klem pengaman.', 'price' => 100000]
        );

        // 2. Teknisi 2: Agus Setiawan (Spesialis Listrik - Jakarta Barat)
        $userAgus = User::firstOrCreate(
            ['email' => 'agus.teknisi@fixhome.test'],
            [
                'name' => 'Agus Setiawan',
                'password' => Hash::make('password'),
                'role' => UserRole::TEKNISI,
                'phone' => '081255667788',
                'status' => 'aktif',
            ]
        );
        $userAgus->syncRoles([UserRole::TEKNISI->value]);

        $profileAgus = TechnicianProfile::firstOrCreate(
            ['user_id' => $userAgus->id],
            [
                'service_category_id' => $catListrik?->id,
                'bio' => 'Teknisi instalasi listrik bersertifikat, ahli dalam penanganan korsleting dan panel distribusi.',
                'base_rate' => 85000,
                'verification_status' => VerificationStatus::DISETUJUI,
                'is_available' => true,
                'address' => 'Jl. Kebon Jeruk Raya No. 45, Kebon Jeruk, Jakarta Barat',
                'latitude' => -6.1683000,
                'longitude' => 106.7588000,
            ]
        );

        TechnicianService::firstOrCreate(
            ['technician_profile_id' => $profileAgus->id, 'name' => 'Perbaikan Korsleting Listrik'],
            ['description' => 'Pelacakan jalur korslet dan penataan kabel.', 'price' => 85000]
        );
        TechnicianService::firstOrCreate(
            ['technician_profile_id' => $profileAgus->id, 'name' => 'Penggantian MCB / Box Panel'],
            ['description' => 'Ganti MCB rusak atau upgrade daya pembagi.', 'price' => 110000]
        );

        // 3. Teknisi 3: Dedi Pratama (Spesialis AC - Jakarta Selatan)
        $userDedi = User::firstOrCreate(
            ['email' => 'dedi.teknisi@fixhome.test'],
            [
                'name' => 'Dedi Pratama',
                'password' => Hash::make('password'),
                'role' => UserRole::TEKNISI,
                'phone' => '081277889900',
                'status' => 'aktif',
            ]
        );
        $userDedi->syncRoles([UserRole::TEKNISI->value]);

        $profileDedi = TechnicianProfile::firstOrCreate(
            ['user_id' => $userDedi->id],
            [
                'service_category_id' => $catAc?->id,
                'bio' => 'Spesialis cuci steam AC dan maintenance AC split segala merk.',
                'base_rate' => 90000,
                'verification_status' => VerificationStatus::DISETUJUI,
                'is_available' => true,
                'address' => 'Jl. RS Fatmawati No. 88, Cilandak, Jakarta Selatan',
                'latitude' => -6.2615000,
                'longitude' => 106.8106000,
            ]
        );

        TechnicianService::firstOrCreate(
            ['technician_profile_id' => $profileDedi->id, 'name' => 'Cuci Steam AC Split 0.5 - 2 PK'],
            ['description' => 'Pembersihan evaporator, kondensor, blower, dan saluran drainase.', 'price' => 90000]
        );
        TechnicianService::firstOrCreate(
            ['technician_profile_id' => $profileDedi->id, 'name' => 'Isi Ulang Freon R32 / R410A'],
            ['description' => 'Pengisian freon hingga tekanan optimal sesuai spesifikasi kompresor.', 'price' => 150000]
        );
    }
}
