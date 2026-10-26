<?php

test('halaman showcase komponen /dev/komponen dapat diakses dengan sukses', function () {
    $response = $this->get('/dev/komponen');

    $response->assertStatus(200);
    $response->assertSee('Pustaka Komponen UI FixHome Plus');
    $response->assertSee('green-950');
    $response->assertSee('green-900');
    $response->assertSee('Lihat Marketplace');
    $response->assertSee('Lewati Marketplace');
    $response->assertSee('Pembelian material bersifat opsional');
});
