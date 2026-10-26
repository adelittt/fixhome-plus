<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-[#BFE3CC] bg-[#F2FAF5] dark:border-[#1F6B3E] dark:bg-[#14432A]/30 p-5 shadow-sm">
            <div class="flex items-start gap-3.5">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#14432A] text-white shadow-sm">
                    <x-heroicon-m-building-storefront class="h-6 w-6 text-[#3FAE6D]" />
                </div>
                <div class="space-y-1">
                    <h3 class="text-sm font-extrabold text-[#14432A] dark:text-[#F2FAF5]">
                        Identitas &amp; Lokasi Fisik Gudang Mitra Toko
                    </h3>
                    <p class="text-xs leading-relaxed text-[#5E6B60] dark:text-[#BFE3CC]">
                        Alamat fisik dan nomor kontak toko akan digunakan langsung oleh <strong>Kurir Instan</strong> sebagai titik penjemputan paket material pesanan pelanggan (AB-013). Pastikan alamat lengkap dan nomor telepon selalu aktif.
                    </p>
                </div>
            </div>
        </div>

        <form wire:submit="save">
            {{ $this->form }}

            <div class="mt-6 flex justify-end">
                <x-filament::button type="submit" color="primary">
                    Simpan Perubahan Profil Toko
                </x-filament::button>
            </div>
        </form>
    </div>
</x-filament-panels::page>
