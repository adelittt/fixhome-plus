<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FixHome Plus') }} - Layanan Perbaikan Rumah & Marketplace Material</title>

        <!-- Google Fonts: Plus Jakarta Sans & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

        <!-- Theme Initialization Script to Prevent FOUC -->
        <script>
            (function() {
                try {
                    const storedTheme = localStorage.getItem('fixhome-theme');
                    if (storedTheme === 'dark' || (!storedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                } catch (e) {}
            })();
        </script>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-[#1B2B20] dark:text-[#F2FAF5] bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen flex flex-col transition-colors duration-200">
        <!-- Toast Notification Container -->
        <x-ui.toast />

        <div class="flex-1 flex flex-col pb-16 sm:pb-0">
            <!-- Header Navigation -->
            <livewire:layout.navigation />

            <!-- Page Heading (Optional) -->
            @if (isset($header))
                <header class="bg-white dark:bg-[#14432A]/50 shadow-sm border-b border-[#E3EBE5] dark:border-[#1F6B3E]">
                    <div class="max-w-[1200px] mx-auto py-5 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1">
                {{ $slot }}
            </main>

            <!-- Global Footer (green-950 #0F2A1A) -->
            <footer class="bg-[#0F2A1A] text-[#BFE3CC] border-t border-[#1F6B3E] pt-12 pb-8 mt-16">
                <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
                        <!-- Col 1: Brand -->
                        <div class="space-y-4 md:col-span-1">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-[#2E8B57] flex items-center justify-center text-white font-extrabold text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <span class="font-extrabold text-lg text-white">FixHome<span class="text-[#3FAE6D]">Plus</span></span>
                            </div>
                            <p class="text-xs text-[#BFE3CC]/80 leading-relaxed">
                                Platform digital perbaikan rumah terpercaya yang mengintegrasikan teknisi ahli dengan marketplace material bangunan.
                            </p>
                            <p class="text-[11px] text-[#BFE3CC]/60">
                                Slogan: Rumah Nyaman, Urusan Beres.
                            </p>
                        </div>

                        <!-- Col 2: Layanan Jasa -->
                        <div>
                            <h4 class="text-sm font-bold text-white mb-3">Layanan Populer</h4>
                            <ul class="space-y-2 text-xs">
                                <li><a href="{{ route('service.catalog') }}" class="hover:text-white transition">Perbaikan Listrik</a></li>
                                <li><a href="{{ route('service.catalog') }}" class="hover:text-white transition">Pipa & Saluran Air</a></li>
                                <li><a href="{{ route('service.catalog') }}" class="hover:text-white transition">Servis & Cuci AC</a></li>
                                <li><a href="{{ route('service.catalog') }}" class="hover:text-white transition">Perbaikan Atap & Bocor</a></li>
                                <li><a href="{{ route('service.catalog') }}" class="hover:text-white transition">Pengecatan Rumah</a></li>
                            </ul>
                        </div>

                        <!-- Col 3: Marketplace -->
                        <div>
                            <h4 class="text-sm font-bold text-white mb-3">Marketplace Toko Mitra</h4>
                            <ul class="space-y-2 text-xs">
                                <li><a href="{{ route('marketplace.index') }}" class="hover:text-white transition">Katalog Material</a></li>
                                <li><a href="{{ route('marketplace.index') }}" class="hover:text-white transition">Pipa & Sambungan</a></li>
                                <li><a href="{{ route('marketplace.index') }}" class="hover:text-white transition">Kabel & Saklar</a></li>
                                <li><a href="{{ route('marketplace.index') }}" class="hover:text-white transition">Cat & Pelapis Tembok</a></li>
                                <li><span class="text-[#3FAE6D] text-[11px]">Pembelian material bersifat opsional.</span></li>
                            </ul>
                        </div>

                        <!-- Col 4: Informasi & Bantuan -->
                        <div>
                            <h4 class="text-sm font-bold text-white mb-3">Pusat Bantuan</h4>
                            <ul class="space-y-2 text-xs">
                                <li><a href="{{ route('home') }}#faq" class="hover:text-white transition">Tanya Jawab (FAQ)</a></li>
                                <li><a href="{{ route('home') }}#cara-kerja" class="hover:text-white transition">Cara Kerja Sistem</a></li>
                                <li><a href="/dev/komponen" class="text-[#3FAE6D] hover:underline transition">Pustaka Komponen UI</a></li>
                                <li><span class="text-[#BFE3CC]/60 text-[11px]">Wilayah operasional: Jabodetabek</span></li>
                            </ul>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-[#1F6B3E]/60 flex flex-col sm:flex-row items-center justify-between text-[11px] text-[#BFE3CC]/60 gap-3">
                        <p>&copy; {{ date('Y') }} FixHome Plus. Seluruh hak cipta dilindungi undang-undang.</p>
                        <div class="flex items-center gap-4">
                            <span class="inline-flex items-center gap-1.5 text-[#3FAE6D]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#3FAE6D]"></span>
                                Sistem Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Mobile Bottom Navigation (Hidden on desktop, touch target >= 44px) -->
        <nav class="sm:hidden fixed bottom-0 inset-x-0 bg-white/95 dark:bg-[#14432A]/95 border-t border-[#E3EBE5] dark:border-[#1F6B3E] z-40 backdrop-blur-md shadow-lg" aria-label="Navigasi Bawah">
            <div class="grid grid-cols-4 h-15">
                <a href="{{ route('home') }}" class="flex flex-col items-center justify-center touch-target text-xs transition {{ request()->routeIs('home') ? 'text-[#14432A] dark:text-[#3FAE6D] font-bold' : 'text-[#5E6B60] dark:text-[#BFE3CC]' }}" wire:navigate>
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>

                <a href="{{ route('service.catalog') }}" class="flex flex-col items-center justify-center touch-target text-xs transition {{ request()->routeIs('service.*') ? 'text-[#14432A] dark:text-[#3FAE6D] font-bold' : 'text-[#5E6B60] dark:text-[#BFE3CC]' }}" wire:navigate>
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari Jasa</span>
                </a>

                <a href="{{ route('marketplace.index') }}" class="flex flex-col items-center justify-center touch-target text-xs transition {{ request()->routeIs('marketplace.*') ? 'text-[#14432A] dark:text-[#3FAE6D] font-bold' : 'text-[#5E6B60] dark:text-[#BFE3CC]' }}" wire:navigate>
                    <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>Material</span>
                </a>

                @auth
                    @if (auth()->user()->isTeknisi())
                        <a href="{{ route('technician.dashboard') }}" class="flex flex-col items-center justify-center touch-target text-xs transition {{ request()->routeIs('technician.dashboard') ? 'text-[#14432A] dark:text-[#3FAE6D] font-bold' : 'text-[#5E6B60] dark:text-[#BFE3CC]' }}" wire:navigate>
                            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span>Dasbor</span>
                        </a>
                    @else
                        <a href="{{ route('customer.orders') }}" class="flex flex-col items-center justify-center touch-target text-xs transition {{ request()->routeIs('customer.orders') || request()->routeIs('customer.product-orders') ? 'text-[#14432A] dark:text-[#3FAE6D] font-bold' : 'text-[#5E6B60] dark:text-[#BFE3CC]' }}" wire:navigate>
                            <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span>Pesanan</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="flex flex-col items-center justify-center touch-target text-xs transition text-[#5E6B60] dark:text-[#BFE3CC]" wire:navigate>
                        <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>Masuk</span>
                    </a>
                @endauth
            </div>
        </nav>

        @stack('scripts')
    </body>
</html>
