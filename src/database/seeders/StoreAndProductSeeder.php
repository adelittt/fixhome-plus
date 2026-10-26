<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StoreAndProductSeeder extends Seeder
{
    public function run(): void
    {
        $catPipa = ProductCategory::where('slug', 'pipa-sanitari')->first();
        $catListrik = ProductCategory::where('slug', 'kelistrikan-komponen')->first();

        // 1. Mitra Toko 1: Toko Bangunan Jaya Abadi (Tangerang)
        $userTokoA = User::firstOrCreate(
            ['email' => 'toko.jayaabadi@fixhome.test'],
            [
                'name' => 'Hendra Gunawan',
                'password' => Hash::make('password'),
                'role' => UserRole::MITRA_TOKO,
                'phone' => '081311223344',
                'status' => 'aktif',
            ]
        );
        $userTokoA->syncRoles([UserRole::MITRA_TOKO->value]);

        $storeA = Store::firstOrCreate(
            ['user_id' => $userTokoA->id],
            [
                'name' => 'TB Jaya Abadi',
                'slug' => 'tb-jaya-abadi',
                'address' => 'Jl. Merdeka No. 100, Sukasari, Kota Tangerang',
                'latitude' => -6.1783000,
                'longitude' => 106.6318000,
                'phone' => '0215523456',
                'verification_status' => VerificationStatus::DISETUJUI,
                'is_active' => true,
            ]
        );

        $productsStoreA = [
            [
                'name' => 'Pipa PVC Wavin AW 1/2 Inch 4 Meter',
                'slug' => 'pipa-pvc-wavin-aw-half-inch',
                'product_category_id' => $catPipa?->id,
                'price' => 38000, // Rp38.000
                'stock' => 50,
                'description' => 'Pipa PVC kualitas tebal tipe AW untuk saluran air bersih bertekanan tinggi.',
                'is_active' => true,
            ],
            [
                'name' => 'Lem Pipa PVC Isarplas Tube 40 Gram',
                'slug' => 'lem-pipa-pvc-isarplas-40g',
                'product_category_id' => $catPipa?->id,
                'price' => 12500, // Rp12.500
                'stock' => 120,
                'description' => 'Lem perekat khusus pipa dan sambungan fitting PVC cepat kering.',
                'is_active' => true,
            ],
            [
                'name' => 'Stop Kran Kuningan Onda 1/2 Inch',
                'slug' => 'stop-kran-kuningan-onda-half-inch',
                'product_category_id' => $catPipa?->id,
                'price' => 55000, // Rp55.000
                'stock' => 35,
                'description' => 'Ball valve stop kran bodi kuningan anti karat merek Onda original.',
                'is_active' => true,
            ],
            [
                'name' => 'Seal Tape Onda 1/2 Inch x 10 Meter',
                'slug' => 'seal-tape-onda-10m',
                'product_category_id' => $catPipa?->id,
                'price' => 6000, // Rp6.000
                'stock' => 200,
                'description' => 'Isolasi drat kran mencegah rembesan air pada sambungan pipa.',
                'is_active' => true,
            ],
            [
                'name' => 'Knee L Bow PVC Rucika 1/2 Inch AW',
                'slug' => 'knee-l-bow-pvc-rucika-half-inch',
                'product_category_id' => $catPipa?->id,
                'price' => 4500, // Rp4.500
                'stock' => 150,
                'description' => 'Sambungan siku pipa PVC 90 derajat tipe AW tebal.',
                'is_active' => true,
            ],
        ];

        foreach ($productsStoreA as $item) {
            Product::firstOrCreate(
                ['store_id' => $storeA->id, 'slug' => $item['slug']],
                $item
            );
        }

        // 2. Mitra Toko 2: Toko Sumber Rejeki Listrik & Material (Jakarta Barat)
        $userTokoB = User::firstOrCreate(
            ['email' => 'toko.sumberrejeki@fixhome.test'],
            [
                'name' => 'Linda Wijaya',
                'password' => Hash::make('password'),
                'role' => UserRole::MITRA_TOKO,
                'phone' => '081377889900',
                'status' => 'aktif',
            ]
        );
        $userTokoB->syncRoles([UserRole::MITRA_TOKO->value]);

        $storeB = Store::firstOrCreate(
            ['user_id' => $userTokoB->id],
            [
                'name' => 'TB Sumber Rejeki',
                'slug' => 'tb-sumber-rejeki',
                'address' => 'Jl. Panjang Arteri No. 50, Kebon Jeruk, Jakarta Barat',
                'latitude' => -6.1855000,
                'longitude' => 106.7712000,
                'phone' => '0215309876',
                'verification_status' => VerificationStatus::DISETUJUI,
                'is_active' => true,
            ]
        );

        $productsStoreB = [
            [
                'name' => 'Kabel Listrik NYM Eterna 2x1.5mm 50 Meter',
                'slug' => 'kabel-nym-eterna-2x1-5-50m',
                'product_category_id' => $catListrik?->id,
                'price' => 325000, // Rp325.000
                'stock' => 20,
                'description' => 'Kabel kawat tembaga murni SNI isolasi ganda untuk instalasi dalam ruangan.',
                'is_active' => true,
            ],
            [
                'name' => 'MCB Schneider 1 Phase Domae 10 Ampere',
                'slug' => 'mcb-schneider-10a',
                'product_category_id' => $catListrik?->id,
                'price' => 70000, // Rp70.000
                'stock' => 45,
                'description' => 'Pemutus arus listrik otomatis proteksi kelebihan beban dan korslet.',
                'is_active' => true,
            ],
            [
                'name' => 'Stop Kontak Panasonic Style CP Inbow',
                'slug' => 'stop-kontak-panasonic-inbow',
                'product_category_id' => $catListrik?->id,
                'price' => 26500, // Rp26.500
                'stock' => 75,
                'description' => 'Stop kontak tanam standar SNI dengan pengaman anak (child protection).',
                'is_active' => true,
            ],
            [
                'name' => 'Saklar Dobel Panasonic Seri Wide',
                'slug' => 'saklar-dobel-panasonic-wide',
                'product_category_id' => $catListrik?->id,
                'price' => 29000, // Rp29.000
                'stock' => 60,
                'description' => 'Saklar ganda dua tombol warna putih minimalis seri wide.',
                'is_active' => true,
            ],
            [
                'name' => 'Isolasi Listrik Unibell Hitam 20 Meter',
                'slug' => 'isolasi-listrik-unibell-hitam',
                'product_category_id' => $catListrik?->id,
                'price' => 8500, // Rp8.500
                'stock' => 130,
                'description' => 'Lakban isolator listrik tahan panas dan tegangan tinggi.',
                'is_active' => true,
            ],
        ];

        foreach ($productsStoreB as $item) {
            Product::firstOrCreate(
                ['store_id' => $storeB->id, 'slug' => $item['slug']],
                $item
            );
        }
    }
}
