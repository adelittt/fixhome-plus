<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $serviceCategories = [
            [
                'name' => 'Perbaikan Pipa & Saluran Air',
                'slug' => 'perbaikan-pipa',
                'icon' => 'heroicon-o-wrench',
                'description' => 'Layanan perbaikan pipa bocor, instalasi saluran air bersih, kran rusak, dan wastafel mampet.',
            ],
            [
                'name' => 'Perbaikan Instalasi Listrik',
                'slug' => 'instalasi-listrik',
                'icon' => 'heroicon-o-bolt',
                'description' => 'Layanan perbaikan korsleting listrik, penggantian stop kontak, MCB, saklar, dan lampu.',
            ],
            [
                'name' => 'Perawatan & Cuci AC',
                'slug' => 'perawatan-ac',
                'icon' => 'heroicon-o-sparkles',
                'description' => 'Layanan pembersihan AC split, isi ulang freon, perbaikan AC tidak dingin atau bocor air.',
            ],
            [
                'name' => 'Perbaikan Atap & Bangunan',
                'slug' => 'perbaikan-atap',
                'icon' => 'heroicon-o-home',
                'description' => 'Layanan renovasi genteng bocor, plafon jebol, talang air, plester dinding retak.',
            ],
            [
                'name' => 'Pengecatan Rumah & Ruangan',
                'slug' => 'pengecatan-rumah',
                'icon' => 'heroicon-o-paint-brush',
                'description' => 'Layanan pengecatan dinding interior, eksterior, pagar, dan kusen.',
            ],
        ];

        foreach ($serviceCategories as $sc) {
            ServiceCategory::firstOrCreate(['slug' => $sc['slug']], $sc);
        }

        $productCategories = [
            [
                'name' => 'Pipa & Sanitari',
                'slug' => 'pipa-sanitari',
                'icon' => 'heroicon-o-funnel',
                'description' => 'Pipa PVC, fitting elbow, knee, kran air, lem pipa, seal tape, dan perlengkapan saluran.',
            ],
            [
                'name' => 'Kelistrikan & Komponen',
                'slug' => 'kelistrikan-komponen',
                'icon' => 'heroicon-o-cpu-chip',
                'description' => 'Kabel tembaga, MCB, saklar, stop kontak, fiting lampu, isolasi listrik.',
            ],
            [
                'name' => 'Cat & Bahan Pelapis',
                'slug' => 'cat-pelapis',
                'icon' => 'heroicon-o-swatch',
                'description' => 'Cat tembok, cat minyak, kuas, roll, pelapis anti bocor, tiner.',
            ],
            [
                'name' => 'Semen & Perekat Bangunan',
                'slug' => 'semen-perekat',
                'icon' => 'heroicon-o-cube',
                'description' => 'Semen instan, compound gypsum, semen putih, waterproofing.',
            ],
            [
                'name' => 'Perkakas & Alat Kerja',
                'slug' => 'perkakas-alat',
                'icon' => 'heroicon-o-cog',
                'description' => 'Obeng, tang, meteran, palu, klem pipa, gergaji, sarung tangan safety.',
            ],
        ];

        foreach ($productCategories as $pc) {
            ProductCategory::firstOrCreate(['slug' => $pc['slug']], $pc);
        }
    }
}
