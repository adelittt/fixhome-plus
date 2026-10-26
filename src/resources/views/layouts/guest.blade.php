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
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
                background-color: #FAF6EA;
            }
        </style>
    </head>
    <body class="min-h-full flex flex-col justify-between antialiased text-slate-800 bg-[#FAF6EA] selection:bg-emerald-600 selection:text-white">
        <!-- Background Ambient Elements -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden -z-10">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-emerald-200/40 blur-3xl"></div>
            <div class="absolute top-1/3 -left-40 w-96 h-96 rounded-full bg-amber-200/30 blur-3xl"></div>
            <div class="absolute -bottom-40 right-1/4 w-96 h-96 rounded-full bg-emerald-100/50 blur-3xl"></div>
        </div>

        <!-- Top Navigation Bar -->
        <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="group transition transform hover:scale-[1.02]" wire:navigate>
                <x-application-logo />
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-slate-600 hover:text-emerald-800 transition px-3 py-2 rounded-xl hover:bg-emerald-50/60" wire:navigate>
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('marketplace.index') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-emerald-800 bg-white/80 border border-emerald-200/80 px-3.5 py-1.5 rounded-xl shadow-2xs hover:bg-emerald-50 transition" wire:navigate>
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span>Marketplace Material</span>
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
        <footer class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-xs text-slate-500">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-emerald-200/50 pt-6">
                <div class="flex items-center gap-2 text-slate-600">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-600"></span>
                    <span>FixHome Plus &copy; {{ date('Y') }} &bull; Platform Perbaikan Rumah &amp; Marketplace Material</span>
                </div>
                <div class="flex items-center gap-4 text-slate-500">
                    <span class="inline-flex items-center gap-1 text-[11px]">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Keamanan Terverifikasi
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px]">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Komisi Transparan
                    </span>
                </div>
            </div>
        </footer>
    </body>
</html>
