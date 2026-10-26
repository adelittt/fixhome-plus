<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('semua halaman publik utama dapat diakses dengan status 200 dan memuat struktur semantik', function () {
    // 1. Landing Page / Beranda
    $homeRes = $this->get('/');
    $homeRes->assertStatus(200);
    $homeRes->assertSee('FixHome');
    $homeRes->assertSee('Plus');
    $homeRes->assertSee('Layanan Perbaikan Rumah');
    $homeRes->assertSee('Navigasi Bawah');

    // 2. Katalog Layanan
    $serviceRes = $this->get('/layanan');
    $serviceRes->assertStatus(200);
    $serviceRes->assertSee('Cari nama teknisi');

    // 3. Marketplace Material
    $marketRes = $this->get('/marketplace');
    $marketRes->assertStatus(200);
    $marketRes->assertSee('Material Bangunan Berkualitas');

    // 4. Showcase Pustaka Komponen UI
    $devRes = $this->get('/dev/komponen');
    $devRes->assertStatus(200);
    $devRes->assertSee('Pustaka Komponen UI FixHome Plus');
});

test('tata letak memuat script inisialisasi dark mode dan wadah notifikasi toast', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('fixhome-theme');
    $response->assertSee('document.documentElement.classList.add(\'dark\')', false);
    $response->assertSee('aria-live="polite"', false);
});

test('pengguna terotentikasi dapat mengakses halaman profil dengan kartu token desain', function () {
    $user = User::factory()->create(['role' => UserRole::PELANGGAN]);

    $response = $this->actingAs($user)->get('/profile');

    $response->assertStatus(200);
    $response->assertSee('Pengaturan Profil');
    $response->assertSee('Informasi Profil');
    $response->assertSee('Perbarui Kata Sandi');
});
