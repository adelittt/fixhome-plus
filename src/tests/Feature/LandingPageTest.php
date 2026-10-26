<?php

use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('landing page publik dapat diakses dengan sukses dan memuat seluruh bagian utama', function () {
    ServiceCategory::create([
        'name' => 'Perbaikan Pipa & Saluran Air',
        'slug' => 'perbaikan-pipa',
        'description' => 'Layanan perbaikan pipa bocor dan saluran air.',
        'is_active' => true,
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('Perbaikan Rumah Beres');
    $response->assertSee('Cara Kerja FixHome Plus dalam 4 Langkah');
    $response->assertSee('Pilih Teknisi');
    $response->assertSee('Diskusi &amp; Jadwal', false);
    $response->assertSee('Rekomendasi Material');
    $response->assertSee('Pekerjaan Beres');
    $response->assertSee('Material Bangunan Langsung dari Toko Mitra');
    $response->assertSee('Pembelian material melalui marketplace bersifat');
    $response->assertSee('opsional');
    $response->assertSee('Pertanyaan yang Sering Diajukan');
    $response->assertSee('(Contoh)');
});
