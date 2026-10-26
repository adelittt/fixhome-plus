<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FixHome Plus') }} - Masuk &amp; Daftar</title>

        <!-- Google Fonts: Plus Jakarta Sans & Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

        <!-- Theme Initialization Script -->
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
    <body class="min-h-full flex flex-col justify-between antialiased text-[#1B2B20] dark:text-[#F2FAF5] bg-[#F2FAF5] dark:bg-[#0F2A1A] transition-colors duration-200">
        <!-- Background Ambient Elements -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-[#BFE3CC]/30 dark:bg-[#1F6B3E]/20 blur-3xl"></div>
            <div class="absolute top-1/3 -left-40 w-96 h-96 rounded-full bg-[#DFF2E6]/40 dark:bg-[#14432A]/40 blur-3xl"></div>
            <div class="absolute -bottom-40 right-1/4 w-96 h-96 rounded-full bg-[#BFE3CC]/20 dark:bg-[#1F6B3E]/10 blur-3xl"></div>
        </div>

        <!-- Top Navigation Bar -->
        <header class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group" wire:navigate>
                <div class="w-9 h-9 rounded-full bg-[#14432A] dark:bg-[#2E8B57] flex items-center justify-center text-white shadow-sm shadow-[#14432A]/20 group-hover:scale-105 transition-transform duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span class="font-extrabold text-xl tracking-tight text-[#14432A] dark:text-[#F2FAF5]">
                    FixHome<span class="text-[#2E8B57] dark:text-[#3FAE6D]">Plus</span>
                </span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-[#5E6B60] hover:text-[#14432A] dark:text-[#BFE3CC] dark:hover:text-white transition px-3 py-2 rounded-xl hover:bg-[#DFF2E6]/50" wire:navigate>
                    <svg class="w-4 h-4 text-[#1F6B3E] dark:text-[#3FAE6D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Beranda</span>
                </a>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-grow flex items-center justify-center px-4 sm:px-6 lg:px-8 py-6 sm:py-10">
            <div class="w-full max-w-5xl">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-[#E3EBE5] dark:border-[#1F6B3E] pt-6">
                <div class="flex items-center gap-2">
                    <span class="inline-block w-2 h-2 rounded-full bg-[#2E8B57]"></span>
                    <span>FixHome Plus &copy; {{ date('Y') }} &bull; Platform Perbaikan Rumah &amp; Marketplace Material</span>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="inline-flex items-center gap-1 text-[11px] text-[#1F6B3E] dark:text-[#3FAE6D]">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Keamanan Terverifikasi
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px] text-[#1F6B3E] dark:text-[#3FAE6D]">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Komisi Transparan
                    </span>
                </div>
            </div>
        </footer>
    </body>
</html>
