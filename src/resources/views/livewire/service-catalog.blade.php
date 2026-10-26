<div class="bg-[#F2FAF5] dark:bg-[#0F2A1A] text-[#1B2B20] dark:text-[#F2FAF5] min-h-screen">
    <!-- 1. HERO SECTION -->
    <section class="relative overflow-hidden pt-8 pb-16 sm:py-20 lg:py-24 border-b border-[#E3EBE5] dark:border-[#1F6B3E] bg-gradient-to-b from-white via-[#F2FAF5] to-[#F2FAF5] dark:from-[#14432A]/50 dark:via-[#0F2A1A] dark:to-[#0F2A1A]">
        <!-- Decorative Ambient Light -->
        <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 rounded-full bg-[#BFE3CC]/40 dark:bg-[#1F6B3E]/20 blur-3xl -z-10"></div>
        <div class="pointer-events-none absolute top-1/2 -right-24 w-96 h-96 rounded-full bg-[#DFF2E6]/50 dark:bg-[#14432A]/30 blur-3xl -z-10"></div>

        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left Content: Headline, Subtitle, Search Bar -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-bold bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 text-[#14432A] dark:text-[#3FAE6D] border border-[#BFE3CC] dark:border-[#1F6B3E] shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-[#2E8B57] animate-pulse"></span>
                        <span>Platform Perbaikan Rumah Terintegrasi Marketplace Material</span>
                    </div>

                    <h1 class="text-3xl sm:text-5xl lg:text-5xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] tracking-tight leading-[1.15]">
                        Perbaikan Rumah Beres, <br class="hidden sm:inline" />
                        <span class="text-[#2E8B57] dark:text-[#3FAE6D]">Material Pas,</span> Urusan Beres.
                    </h1>

                    <p class="text-sm sm:text-base text-[#5E6B60] dark:text-[#BFE3CC] max-w-xl mx-auto lg:mx-0 leading-relaxed">
                        Hubungkan kendala rumah Anda dengan teknisi ahli terverifikasi di Jabodetabek. Dapatkan rekomendasi material akurat langsung dari ahlinya dengan transparansi penuh.
                    </p>

                    <!-- Interactive Livewire Search Box -->
                    <div class="bg-white dark:bg-[#14432A] p-2.5 rounded-2xl shadow-elevated border border-[#E3EBE5] dark:border-[#1F6B3E] max-w-xl mx-auto lg:mx-0">
                        <div class="flex items-center gap-2">
                            <div class="pl-2.5 text-[#5E6B60] dark:text-[#BFE3CC]">
                                <svg class="w-5 h-5 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <input
                                type="text"
                                wire:model.live.debounce.300ms="search"
                                placeholder="Cari nama teknisi, keahlian (listrik, pipa, AC, atap)..."
                                class="w-full border-none focus:ring-0 text-sm text-[#1B2B20] dark:text-[#F2FAF5] placeholder-[#5E6B60]/70 dark:placeholder-[#BFE3CC]/60 bg-transparent py-2 min-h-[44px]"
                            />
                            @if ($search)
                                <button type="button" wire:click="$set('search', '')" class="text-xs text-[#5E6B60] hover:text-[#B3402F] px-2 py-1">
                                    Hapus
                                </button>
                            @endif
                            <x-ui.button
                                variant="primary"
                                size="sm"
                                class="shrink-0"
                                onclick="document.getElementById('katalog-teknisi')?.scrollIntoView({ behavior: 'smooth' })"
                            >
                                Cari
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Quick Category Tags -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 pt-1 text-xs">
                        <span class="text-[#5E6B60] dark:text-[#BFE3CC] font-semibold">Paling dicari:</span>
                        @foreach ($categories->take(4) as $cat)
                            <button
                                type="button"
                                wire:click="selectCategory({{ $cat->id }})"
                                class="px-2.5 py-1 rounded-lg bg-[#F2FAF5] dark:bg-[#14432A]/60 text-[#14432A] dark:text-[#BFE3CC] hover:bg-[#DFF2E6] border border-[#BFE3CC] transition"
                            >
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Right Content: Realistic Photo & Floating Badges -->
                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-md">
                        <!-- Main Photo Card -->
                        <div class="relative rounded-3xl overflow-hidden shadow-elevated border-2 border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]">
                            <img
                                src="/images/hero-technician.jpg"
                                alt="Teknisi Profesional FixHome Plus"
                                class="w-full h-[400px] object-cover object-center"
                                loading="eager"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0F2A1A]/80 via-transparent to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white">
                                <p class="text-xs font-bold text-[#BFE3CC]">Standar Kualitas FixHome</p>
                                <p class="text-sm font-extrabold">Teknisi Terverifikasi &amp; Berpengalaman</p>
                            </div>
                        </div>

                        <!-- Floating Badge 1: Verifikasi Teknisi -->
                        <div class="absolute -top-4 -left-4 sm:-left-6 bg-white dark:bg-[#14432A] p-3 rounded-2xl shadow-elevated border border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center gap-3 backdrop-blur-md">
                            <div class="w-10 h-10 rounded-xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/50 text-[#1F6B3E] dark:text-[#3FAE6D] flex items-center justify-center">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#14432A] dark:text-[#F2FAF5]">100% Terverifikasi</p>
                                <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">Lulus Seleksi Admin</p>
                            </div>
                        </div>

                        <!-- Floating Badge 2: Komisi Transparan -->
                        <div class="absolute -bottom-4 -right-4 sm:-right-6 bg-white dark:bg-[#14432A] p-3 rounded-2xl shadow-elevated border border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center gap-3 backdrop-blur-md">
                            <div class="w-10 h-10 rounded-xl bg-[#FEF8E7] text-[#9A6F14] flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#14432A] dark:text-[#F2FAF5]">Transparansi Biaya</p>
                                <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">Komisi Jasa 5% Jelas</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. KATEGORI LAYANAN INTERAKTIF -->
    <section class="py-16 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold text-[#1F6B3E] dark:text-[#3FAE6D] uppercase tracking-wider">Kategori Pilihan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] mt-1">
                Layanan Perbaikan Rumah Terpopuler
            </h2>
            <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] mt-2">
                Pilih kategori sesuai masalah rumah Anda untuk langsung terhubung dengan spesialis terbaik.
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
            @php
                $categoryIcons = [
                    'perbaikan-pipa' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>',
                    'instalasi-listrik' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                    'perawatan-ac' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                    'perbaikan-atap' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                    'pengecatan-rumah' => '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>',
                ];
            @endphp

            @foreach ($categories as $cat)
                <button
                    type="button"
                    wire:click="selectCategory({{ $selectedCategory === $cat->id ? 'null' : $cat->id }})"
                    class="p-5 rounded-2xl border text-left transition duration-200 card-hover-effect flex flex-col justify-between group min-h-[160px] {{
                        $selectedCategory === $cat->id
                            ? 'bg-[#14432A] text-white border-[#14432A] shadow-elevated ring-2 ring-[#3FAE6D]'
                            : 'bg-white dark:bg-[#14432A]/30 border-[#E3EBE5] dark:border-[#1F6B3E] hover:border-[#BFE3CC]'
                    }}"
                >
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 transition-colors {{
                        $selectedCategory === $cat->id
                            ? 'bg-white/20 text-white'
                            : 'bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 text-[#14432A] dark:text-[#3FAE6D] group-hover:bg-[#14432A] group-hover:text-white'
                    }}">
                        {!! $categoryIcons[$cat->slug] ?? '<svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>' !!}
                    </div>

                    <div>
                        <h3 class="text-sm font-bold tracking-tight mb-1 {{ $selectedCategory === $cat->id ? 'text-white' : 'text-[#1B2B20] dark:text-[#F2FAF5]' }}">
                            {{ $cat->name }}
                        </h3>
                        <p class="text-[11px] line-clamp-2 {{ $selectedCategory === $cat->id ? 'text-[#BFE3CC]' : 'text-[#5E6B60] dark:text-[#BFE3CC]' }}">
                            {{ $cat->description }}
                        </p>
                    </div>
                </button>
            @endforeach
        </div>
    </section>

    <!-- 3. CARA KERJA 4 LANGKAH (ANIMASI BERTAHAP) -->
    <section id="cara-kerja" class="py-16 bg-white dark:bg-[#14432A]/20 border-y border-[#E3EBE5] dark:border-[#1F6B3E]">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-bold text-[#1F6B3E] dark:text-[#3FAE6D] uppercase tracking-wider">Alur Mudah</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] mt-1">
                    Cara Kerja FixHome Plus dalam 4 Langkah
                </h2>
                <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] mt-2">
                    Proses transparan mulai dari pencarian hingga perbaikan selesai tanpa biaya tersembunyi.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                <!-- Step 1 -->
                <div class="bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#BFE3CC] dark:border-[#1F6B3E] p-6 rounded-2xl relative flex flex-col justify-between card-hover-effect">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-[#14432A] text-white font-extrabold text-sm flex items-center justify-center mb-4 shadow-sm">
                            1
                        </div>
                        <h3 class="text-base font-bold text-[#14432A] dark:text-[#F2FAF5] mb-2">Pilih Teknisi</h3>
                        <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed">
                            Cari teknisi terverifikasi di Jabodetabek berdasarkan keahlian, ulasan, tarif, dan estimasi jarak rute.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[#BFE3CC]/60 text-[11px] font-semibold text-[#1F6B3E] dark:text-[#3FAE6D]">
                        Jarak &ge; 10 km dikenakan biaya transparan
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#BFE3CC] dark:border-[#1F6B3E] p-6 rounded-2xl relative flex flex-col justify-between card-hover-effect">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-[#14432A] text-white font-extrabold text-sm flex items-center justify-center mb-4 shadow-sm">
                            2
                        </div>
                        <h3 class="text-base font-bold text-[#14432A] dark:text-[#F2FAF5] mb-2">Diskusi &amp; Jadwal</h3>
                        <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed">
                            Gunakan obrolan langsung (live chat) untuk berkonsultasi mengenai keluhan dan menyepakati waktu kunjungan.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[#BFE3CC]/60 text-[11px] font-semibold text-[#1F6B3E] dark:text-[#3FAE6D]">
                        Komunikasi aman di dalam sistem
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#BFE3CC] dark:border-[#1F6B3E] p-6 rounded-2xl relative flex flex-col justify-between card-hover-effect">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-[#2E8B57] text-white font-extrabold text-sm flex items-center justify-center mb-4 shadow-sm">
                            3
                        </div>
                        <h3 class="text-base font-bold text-[#14432A] dark:text-[#F2FAF5] mb-2">Rekomendasi Material</h3>
                        <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed">
                            Teknisi membuat daftar spesifikasi material yang pas. Anda bebas memilih "Lihat" atau "Lewati" marketplace.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[#BFE3CC]/60 text-[11px] font-bold text-[#E0A526]">
                        * Pembelian material bersifat opsional
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="bg-[#F2FAF5] dark:bg-[#14432A]/50 border border-[#BFE3CC] dark:border-[#1F6B3E] p-6 rounded-2xl relative flex flex-col justify-between card-hover-effect">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-[#14432A] text-white font-extrabold text-sm flex items-center justify-center mb-4 shadow-sm">
                            4
                        </div>
                        <h3 class="text-base font-bold text-[#14432A] dark:text-[#F2FAF5] mb-2">Pekerjaan Beres</h3>
                        <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed">
                            Teknisi menyelesaikan perbaikan sesuai standar. Berikan ulasan objektif untuk membantu pelanggan lain.
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-[#BFE3CC]/60 text-[11px] font-semibold text-[#1F6B3E] dark:text-[#3FAE6D]">
                        Simulasi pembayaran aman
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. BAGIAN "MATERIAL LANGSUNG DARI TOKO MITRA" (OPSIONAL) -->
    <section class="py-16 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-white via-[#F2FAF5] to-[#DFF2E6] dark:from-[#14432A] dark:via-[#14432A]/80 dark:to-[#0F2A1A] rounded-3xl p-8 sm:p-12 border border-[#BFE3CC] dark:border-[#1F6B3E] shadow-elevated">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-7 space-y-5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-[#DFF2E6] text-[#14432A] border border-[#BFE3CC]">
                        <svg class="w-4 h-4 text-[#2E8B57]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Marketplace Terintegrasi</span>
                    </div>

                    <h2 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] tracking-tight">
                        Material Bangunan Langsung dari Toko Mitra
                    </h2>

                    <!-- PENEGASAN ATURAN BISNIS: SIFAT OPSIONAL -->
                    <div class="p-4 rounded-xl bg-white/80 dark:bg-[#0F2A1A]/60 border-l-4 border-[#2E8B57] text-xs text-[#1B2B20] dark:text-[#F2FAF5] leading-relaxed shadow-xs">
                        <strong class="font-bold text-[#14432A] dark:text-[#3FAE6D]">Penting untuk diketahui:</strong>
                        Pembelian material melalui marketplace bersifat <strong>opsional</strong>. Pelanggan bebas memilih untuk memesan material dari Mitra Toko terdekat atau menyediakan material secara mandiri tanpa membatalkan maupun menghambat pengerjaan jasa perbaikan rumah.
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                        <div class="p-3 bg-white/70 dark:bg-[#14432A]/50 rounded-xl border border-[#E3EBE5] dark:border-[#1F6B3E]">
                            <p class="text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] mb-1">Mitra Toko Terpercaya</p>
                            <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">Toko bangunan fisik terkurasi dengan verifikasi dokumen resmi.</p>
                        </div>
                        <div class="p-3 bg-white/70 dark:bg-[#14432A]/50 rounded-xl border border-[#E3EBE5] dark:border-[#1F6B3E]">
                            <p class="text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] mb-1">Estimasi Kurir Cepat</p>
                            <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">Pengiriman hari yang sama disimulasikan sesuai jadwal pengerjaan.</p>
                        </div>
                        <div class="p-3 bg-white/70 dark:bg-[#14432A]/50 rounded-xl border border-[#E3EBE5] dark:border-[#1F6B3E]">
                            <p class="text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] mb-1">Pecah Order per Toko</p>
                            <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">Checkout transparan dengan rincian biaya pengiriman per toko.</p>
                        </div>
                    </div>

                    <div class="pt-3">
                        <x-ui.button :href="route('marketplace.index')" variant="primary" size="md" wire:navigate>
                            Jelajahi Marketplace Material
                        </x-ui.button>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="relative rounded-2xl overflow-hidden shadow-card border border-[#E3EBE5] dark:border-[#1F6B3E]">
                        <img
                            src="/images/store-materials.jpg"
                            alt="Mitra Toko Material FixHome Plus"
                            class="w-full h-72 object-cover"
                            loading="lazy"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. TEKNISI UNGGULAN & KATALOG LANGSUNG -->
    <section id="katalog-teknisi" class="py-16 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
            <div>
                <span class="text-xs font-bold text-[#1F6B3E] dark:text-[#3FAE6D] uppercase tracking-wider">Katalog Langsung</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] mt-1">
                    Daftar Teknisi Siap Kerja
                </h2>
                <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] mt-1">
                    @if ($selectedCategory)
                        Menampilkan teknisi kategori: <strong class="text-[#14432A] dark:text-[#3FAE6D]">{{ $categories->firstWhere('id', $selectedCategory)?->name }}</strong>
                    @else
                        Menampilkan seluruh teknisi terverifikasi yang sedang tersedia.
                    @endif
                </p>
            </div>

            <!-- Category Filter Pills -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
                <button
                    wire:click="selectCategory(null)"
                    class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition {{ is_null($selectedCategory) ? 'bg-[#14432A] text-white' : 'bg-white dark:bg-[#14432A]/40 text-[#5E6B60] dark:text-[#BFE3CC] border border-[#E3EBE5] dark:border-[#1F6B3E]' }}"
                >
                    Semua
                </button>
                @foreach ($categories as $cat)
                    <button
                        wire:click="selectCategory({{ $cat->id }})"
                        class="px-3.5 py-1.5 text-xs font-bold rounded-xl transition whitespace-nowrap {{ $selectedCategory === $cat->id ? 'bg-[#14432A] text-white' : 'bg-white dark:bg-[#14432A]/40 text-[#5E6B60] dark:text-[#BFE3CC] border border-[#E3EBE5] dark:border-[#1F6B3E]' }}"
                    >
                        {{ $cat->name }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Wire Loading Indicator with Skeletons -->
        <div wire:loading.grid class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-ui.skeleton type="card" />
            <x-ui.skeleton type="card" />
            <x-ui.skeleton type="card" />
        </div>

        <!-- Technician Grid Results -->
        <div wire:loading.remove>
            @if ($technicians->isEmpty())
                <x-ui.empty-state
                    title="Teknisi Tidak Ditemukan"
                    description="Tidak ada teknisi aktif yang cocok dengan kriteria pencarian Anda saat ini."
                >
                    <x-ui.button wire:click="$set('search', ''); $set('selectedCategory', null)" variant="secondary" size="sm">
                        Reset Filter &amp; Pencarian
                    </x-ui.button>
                </x-ui.empty-state>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($technicians as $tech)
                        @php
                            $servicesList = $tech->services->pluck('name')->toArray();
                            if (empty($servicesList) && $tech->category) {
                                $servicesList = [$tech->category->name];
                            }
                        @endphp
                        <x-ui.technician-card
                            :id="$tech->id"
                            :name="$tech->user->name"
                            :skills="$servicesList"
                            :rating="4.9"
                            :reviewCount="12"
                            :basePrice="$tech->services->first()?->price ?? 50000"
                            :isAvailable="(bool) $tech->is_available"
                            :bookUrl="route('service.book', $tech->user_id)"
                        />
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- 6. ULASAN PELANGGAN (DATA CONTOH) -->
    <section class="py-16 bg-[#F2FAF5] dark:bg-[#0F2A1A] border-t border-[#E3EBE5] dark:border-[#1F6B3E]">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#FEF8E7] text-[#9A6F14] border border-[#F5DE9B] mb-2">
                    Data Simulasi / Contoh
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5]">
                    Pengalaman Pelanggan FixHome
                </h2>
                <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] mt-1">
                    Berikut adalah testimoni contoh untuk menunjukkan tampilan ulasan nyata pelanggan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Review 1 -->
                <x-ui.card>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <x-ui.rating :rating="5.0" size="sm" />
                            <span class="text-[10px] text-[#5E6B60] dark:text-[#BFE3CC]">(Contoh)</span>
                        </div>
                        <p class="text-xs text-[#1B2B20] dark:text-[#F2FAF5] leading-relaxed italic">
                            "Kran wastafel bocor dan teknisi datang tepat waktu. Beliau merekomendasikan sambungan pipa yang tepat lewat marketplace dan saya bisa pesan langsung tanpa ribet ke toko bangunan."
                        </p>
                        <div class="pt-2 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-[#DFF2E6] text-[#14432A] font-bold text-xs flex items-center justify-center">
                                H
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#14432A] dark:text-[#F2FAF5]">Hendra Wijaya</p>
                                <p class="text-[10px] text-[#5E6B60] dark:text-[#BFE3CC]">Layanan Pipa &bull; Tangerang</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Review 2 -->
                <x-ui.card>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <x-ui.rating :rating="5.0" size="sm" />
                            <span class="text-[10px] text-[#5E6B60] dark:text-[#BFE3CC]">(Contoh)</span>
                        </div>
                        <p class="text-xs text-[#1B2B20] dark:text-[#F2FAF5] leading-relaxed italic">
                            "Saya memilih opsi Lewati Marketplace karena kebetulan sudah punya cat sisa di gudang. Sangat adil karena teknisi tetap melayani dengan profesional tanpa memaksa belanja."
                        </p>
                        <div class="pt-2 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-[#DFF2E6] text-[#14432A] font-bold text-xs flex items-center justify-center">
                                S
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#14432A] dark:text-[#F2FAF5]">Siti Rahmawati</p>
                                <p class="text-[10px] text-[#5E6B60] dark:text-[#BFE3CC]">Pengecatan Kamar &bull; Jakarta Selatan</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Review 3 -->
                <x-ui.card>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <x-ui.rating :rating="5.0" size="sm" />
                            <span class="text-[10px] text-[#5E6B60] dark:text-[#BFE3CC]">(Contoh)</span>
                        </div>
                        <p class="text-xs text-[#1B2B20] dark:text-[#F2FAF5] leading-relaxed italic">
                            "AC di kamar tidur netes air terus dan kurang dingin. Setelah diperiksa dan dibersihkan, dinginnya kembali maksimal. Rincian tarif jelas di awal termasuk biaya jarak rute."
                        </p>
                        <div class="pt-2 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-[#DFF2E6] text-[#14432A] font-bold text-xs flex items-center justify-center">
                                A
                            </div>
                            <div>
                                <p class="text-xs font-bold text-[#14432A] dark:text-[#F2FAF5]">Aris Pratama</p>
                                <p class="text-[10px] text-[#5E6B60] dark:text-[#BFE3CC]">Servis AC &bull; Bekasi Barat</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>
    </section>

    <!-- 7. AJAKAN BERGABUNG (MITRA TEKNISI & TOKO) -->
    <section class="py-16 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- CTA Teknisi -->
            <div class="bg-gradient-to-br from-[#14432A] to-[#0F2A1A] rounded-3xl p-8 text-white flex flex-col justify-between shadow-elevated relative overflow-hidden">
                <div class="space-y-4">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-[#2E8B57] text-white">
                        Untuk Teknisi Ahli
                    </span>
                    <h3 class="text-2xl font-extrabold tracking-tight">Bergabung Menjadi Mitra Teknisi</h3>
                    <p class="text-xs text-[#BFE3CC] leading-relaxed">
                        Dapatkan penghasilan dengan komisi adil 95% untuk teknisi (platform hanya mengenakan 5%). Anda yang menentukan jadwal dan ketersediaan kerja sendiri.
                    </p>
                </div>
                <div class="pt-6">
                    <x-ui.button :href="route('register')" variant="secondary" size="md">
                        Daftar sebagai Teknisi
                    </x-ui.button>
                </div>
            </div>

            <!-- CTA Mitra Toko -->
            <div class="bg-gradient-to-br from-white to-[#DFF2E6] dark:from-[#14432A]/60 dark:to-[#0F2A1A] rounded-3xl p-8 border border-[#BFE3CC] dark:border-[#1F6B3E] flex flex-col justify-between shadow-card">
                <div class="space-y-4">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-[#14432A] text-white dark:bg-[#3FAE6D] dark:text-[#0F2A1A]">
                        Untuk Pemilik Toko Material
                    </span>
                    <h3 class="text-2xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] tracking-tight">Buka Kanal Penjualan Digital</h3>
                    <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed">
                        Toko material Anda langsung terhubung dengan kebutuhan perbaikan di perumahan sekitar. Pengelolaan produk, stok, dan pesanan mandiri dengan komisi marketplace hanya 2%.
                    </p>
                </div>
                <div class="pt-6">
                    <x-ui.button :href="route('register')" variant="primary" size="md">
                        Daftar Mitra Toko Bangunan
                    </x-ui.button>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. FAQ ACCORDION INTERAKTIF (ALPINE.JS) -->
    <section id="faq" class="py-16 bg-white dark:bg-[#14432A]/20 border-t border-[#E3EBE5] dark:border-[#1F6B3E]" x-data="{ active: null }">
        <div class="max-w-[800px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-xs font-bold text-[#1F6B3E] dark:text-[#3FAE6D] uppercase tracking-wider">Tanya Jawab</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#14432A] dark:text-[#F2FAF5] mt-1">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] mt-1">
                    Informasi mengenai aturan transaksi, komisi, dan cara kerja sistem.
                </p>
            </div>

            <div class="space-y-3">
                <!-- Q1 -->
                <div class="border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl overflow-hidden bg-[#F2FAF5]/50 dark:bg-[#14432A]/40 transition">
                    <button
                        type="button"
                        @click="active = active === 1 ? null : 1"
                        class="w-full p-4.5 text-left font-bold text-sm text-[#14432A] dark:text-[#F2FAF5] flex items-center justify-between gap-4 focus:outline-none min-h-[44px]"
                    >
                        <span>Apakah saya wajib membeli material dari Toko Mitra FixHome Plus?</span>
                        <svg :class="active === 1 ? 'rotate-180' : ''" class="w-4 h-4 text-[#2E8B57] transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 1" x-collapse class="px-4.5 pb-4.5 text-xs text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed border-t border-[#E3EBE5] dark:border-[#1F6B3E] pt-3">
                        <strong>Tidak.</strong> Sesuai aturan bisnis kami, pembelian material bersifat <em>opsional</em>. Pelanggan dapat menekan tombol <strong>"Lewati Marketplace"</strong> kapan saja dan pesanan jasa tetap berlangsung tanpa hambatan.
                    </div>
                </div>

                <!-- Q2 -->
                <div class="border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl overflow-hidden bg-[#F2FAF5]/50 dark:bg-[#14432A]/40 transition">
                    <button
                        type="button"
                        @click="active = active === 2 ? null : 2"
                        class="w-full p-4.5 text-left font-bold text-sm text-[#14432A] dark:text-[#F2FAF5] flex items-center justify-between gap-4 focus:outline-none min-h-[44px]"
                    >
                        <span>Bagaimana perhitungan biaya tambahan jarak rute?</span>
                        <svg :class="active === 2 ? 'rotate-180' : ''" class="w-4 h-4 text-[#2E8B57] transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 2" x-collapse class="px-4.5 pb-4.5 text-xs text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed border-t border-[#E3EBE5] dark:border-[#1F6B3E] pt-3">
                        Berdasarkan Aturan Bisnis AB-002, apabila jarak rute sebenarnya (bukan garis lurus) dari teknisi menuju alamat pelanggan mencapai 10 km atau lebih, sistem menambahkan biaya tambahan jarak sebesar Rp10.000 yang langsung dicantumkan pada rincian biaya pesanan.
                    </div>
                </div>

                <!-- Q3 -->
                <div class="border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl overflow-hidden bg-[#F2FAF5]/50 dark:bg-[#14432A]/40 transition">
                    <button
                        type="button"
                        @click="active = active === 3 ? null : 3"
                        class="w-full p-4.5 text-left font-bold text-sm text-[#14432A] dark:text-[#F2FAF5] flex items-center justify-between gap-4 focus:outline-none min-h-[44px]"
                    >
                        <span>Bagaimana teknisi memberikan rekomendasi material?</span>
                        <svg :class="active === 3 ? 'rotate-180' : ''" class="w-4 h-4 text-[#2E8B57] transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 3" x-collapse class="px-4.5 pb-4.5 text-xs text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed border-t border-[#E3EBE5] dark:border-[#1F6B3E] pt-3">
                        Setelah berdiskusi atau memeriksa lokasi perbaikan, teknisi mengisi formulir rekomendasi berisi nama material, estimasi jumlah, dan catatan spesifikasi. Teknisi tidak diizinkan membeli memakai dana pribadi maupun memesan atas nama pelanggan.
                    </div>
                </div>

                <!-- Q4 -->
                <div class="border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl overflow-hidden bg-[#F2FAF5]/50 dark:bg-[#14432A]/40 transition">
                    <button
                        type="button"
                        @click="active = active === 4 ? null : 4"
                        class="w-full p-4.5 text-left font-bold text-sm text-[#14432A] dark:text-[#F2FAF5] flex items-center justify-between gap-4 focus:outline-none min-h-[44px]"
                    >
                        <span>Bagaimana sistem komisi di FixHome Plus?</span>
                        <svg :class="active === 4 ? 'rotate-180' : ''" class="w-4 h-4 text-[#2E8B57] transition-transform duration-200 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 4" x-collapse class="px-4.5 pb-4.5 text-xs text-[#5E6B60] dark:text-[#BFE3CC] leading-relaxed border-t border-[#E3EBE5] dark:border-[#1F6B3E] pt-3">
                        Komisi dipisahkan secara tegas: Komisi Jasa sebesar 5% (teknisi memperoleh 95%), sedangkan Komisi Marketplace sebesar 2% dari nilai produk selesai (toko mitra memperoleh 98%). Pelanggan tidak dibebankan potongan komisi, melainkan membayar harga riil yang tertera.
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
