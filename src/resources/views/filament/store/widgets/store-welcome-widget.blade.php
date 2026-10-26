<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#14432A] via-[#175430] to-[#0F2A1A] p-6 sm:p-8 text-white shadow-xl shadow-[#0F2A1A]/10 border border-[#1F6B3E]/40">
        <!-- Background Ambient Glow & Patterns -->
        <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-[#3FAE6D]/15 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 rounded-full bg-[#E0A526]/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3 max-w-2xl">
                <!-- Pill Status Toko -->
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/15 text-xs font-semibold text-[#BFE3CC]">
                    <span class="w-2 h-2 rounded-full bg-[#3FAE6D] animate-pulse"></span>
                    <span>Portal Mitra Toko Material Bangunan</span>
                    <span class="text-white/40">•</span>
                    <span class="text-white font-bold">{{ $store?->name ?? 'Toko Mitra FixHome' }}</span>
                </div>

                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white leading-tight">
                    Selamat Datang, {{ $user?->name ?? 'Mitra Toko' }}! 🏪
                </h2>

                <p class="text-xs sm:text-sm text-[#E3EBE5] leading-relaxed">
                    Kelola stok material bangunan, proses pesanan masuk dari pelanggan perbaikan rumah, dan panggil kurir instan langsung dari panel toko Anda.
                </p>

                <!-- Metadata Toko -->
                <div class="flex flex-wrap items-center gap-4 text-xs text-[#BFE3CC] pt-1">
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#3FAE6D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $store?->address ?? 'Alamat Toko Belum Diatur' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-[#3FAE6D]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span class="font-mono">{{ $store?->phone ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-[#2E8B57]/40 px-2.5 py-0.5 rounded-lg border border-[#3FAE6D]/30">
                        <svg class="w-3.5 h-3.5 text-[#3FAE6D]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="font-bold text-[11px] text-white">Status: {{ ucfirst($store?->verification_status?->value ?? 'Terverifikasi') }}</span>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Cepat (Quick Actions) -->
            <div class="flex flex-col sm:flex-row lg:flex-col gap-2.5 shrink-0">
                <a
                    href="{{ url('/toko/products/create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#2E8B57] hover:bg-[#3FAE6D] active:bg-[#1F6B3E] text-white font-bold text-xs shadow-lg shadow-[#0F2A1A]/30 transition-all transform active:scale-[0.98]"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Produk Material</span>
                </a>

                <a
                    href="{{ url('/toko/product-orders') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 active:bg-white/25 text-white font-semibold text-xs border border-white/20 backdrop-blur-md transition-all"
                >
                    <svg class="w-4 h-4 text-[#BFE3CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span>Kelola Pesanan Masuk</span>
                </a>

                <a
                    href="{{ url('/toko/manage-store-profile') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 active:bg-white/25 text-white font-semibold text-xs border border-white/20 backdrop-blur-md transition-all"
                >
                    <svg class="w-4 h-4 text-[#BFE3CC]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span>Ubah Profil &amp; Lokasi Toko</span>
                </a>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>
