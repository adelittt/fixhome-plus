<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false, isDark: false }" x-init="isDark = document.documentElement.classList.contains('dark')" class="bg-white/95 dark:bg-[#14432A]/95 border-b border-[#E3EBE5] dark:border-[#1F6B3E] sticky top-0 z-40 shadow-sm backdrop-blur-md transition-colors duration-200">
    <!-- Primary Navigation Menu -->
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-8">
                <!-- Logo with Green Circle -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2.5 group" wire:navigate>
                        <div class="w-9 h-9 rounded-full bg-[#14432A] dark:bg-[#2E8B57] flex items-center justify-center text-white shadow-sm shadow-[#14432A]/20 group-hover:scale-105 transition-transform duration-200">
                            <!-- House / FixHome icon -->
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <span class="font-extrabold text-xl tracking-tight text-[#14432A] dark:text-[#F2FAF5]">
                            FixHome<span class="text-[#2E8B57] dark:text-[#3FAE6D]">Plus</span>
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden md:flex items-center space-x-1 sm:-my-px h-16">
                    <a href="{{ route('service.catalog') }}" wire:navigate class="inline-flex items-center px-3.5 h-16 border-b-2 text-sm font-semibold transition {{ request()->routeIs('service.catalog') || request()->routeIs('home') ? 'border-[#14432A] text-[#14432A] dark:border-[#3FAE6D] dark:text-[#3FAE6D]' : 'border-transparent text-[#5E6B60] hover:text-[#14432A] hover:border-[#BFE3CC] dark:text-[#BFE3CC] dark:hover:text-white' }}">
                        Cari Jasa & Teknisi
                    </a>

                    <a href="{{ route('marketplace.index') }}" wire:navigate class="inline-flex items-center px-3.5 h-16 border-b-2 text-sm font-semibold transition {{ request()->routeIs('marketplace.*') ? 'border-[#14432A] text-[#14432A] dark:border-[#3FAE6D] dark:text-[#3FAE6D]' : 'border-transparent text-[#5E6B60] hover:text-[#14432A] hover:border-[#BFE3CC] dark:text-[#BFE3CC] dark:hover:text-white' }}">
                        Marketplace Material
                    </a>

                    @auth
                        @if (auth()->user()->isTeknisi())
                            <a href="{{ route('technician.dashboard') }}" wire:navigate class="inline-flex items-center px-3.5 h-16 border-b-2 text-sm font-semibold transition {{ request()->routeIs('technician.dashboard') ? 'border-[#14432A] text-[#14432A] dark:border-[#3FAE6D] dark:text-[#3FAE6D]' : 'border-transparent text-[#5E6B60] hover:text-[#14432A] hover:border-[#BFE3CC] dark:text-[#BFE3CC] dark:hover:text-white' }}">
                                Dasbor Pekerjaan
                            </a>
                        @else
                            <a href="{{ route('customer.orders') }}" wire:navigate class="inline-flex items-center px-3.5 h-16 border-b-2 text-sm font-semibold transition {{ request()->routeIs('customer.orders') || request()->routeIs('customer.product-orders') ? 'border-[#14432A] text-[#14432A] dark:border-[#3FAE6D] dark:text-[#3FAE6D]' : 'border-transparent text-[#5E6B60] hover:text-[#14432A] hover:border-[#BFE3CC] dark:text-[#BFE3CC] dark:hover:text-white' }}">
                                Pesanan Saya
                            </a>
                        @endif

                        @if (auth()->user()->isMitraToko())
                            <a href="/toko" class="inline-flex items-center px-3 py-1.5 ml-2 text-xs font-bold rounded-lg bg-[#DFF2E6] text-[#14432A] hover:bg-[#BFE3CC] border border-[#BFE3CC] dark:bg-[#1F6B3E]/40 dark:text-[#F2FAF5] transition">
                                Panel Mitra Toko
                            </a>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <a href="/admin" class="inline-flex items-center px-3 py-1.5 ml-2 text-xs font-bold rounded-lg bg-[#EDF3F8] text-[#4A6B8A] hover:bg-[#BCD0E2] border border-[#BCD0E2] transition">
                                Panel Admin
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Right: Dark Mode Toggle, Cart, & Profile Menu -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">
                <!-- Dark Mode Toggle Button -->
                <button
                    type="button"
                    @click="window.toggleDarkMode(); isDark = document.documentElement.classList.contains('dark')"
                    class="p-2.5 rounded-xl text-[#5E6B60] hover:text-[#14432A] dark:text-[#BFE3CC] dark:hover:text-white hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/60 transition"
                    title="Ubah Mode Gelap / Terang"
                    aria-label="Ubah mode gelap/terang"
                >
                    <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                <!-- Keranjang Belanja Material -->
                <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-xl text-[#5E6B60] hover:text-[#14432A] dark:text-[#BFE3CC] dark:hover:text-white hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/60 transition" title="Keranjang Belanja Material" wire:navigate>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    @auth
                        @php
                            $cartCount = \App\Models\CartItem::where('customer_id', auth()->id())->sum('quantity');
                        @endphp
                        @if ($cartCount > 0)
                            <span class="absolute top-1 right-1 w-4 h-4 bg-[#2E8B57] text-white rounded-full text-[10px] font-extrabold flex items-center justify-center shadow-sm">
                                {{ $cartCount > 9 ? '9+' : $cartCount }}
                            </span>
                        @endif
                    @endauth
                </a>

                @auth
                    <x-ui.dropdown align="right" width="w-52">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2.5 px-3 py-1.5 border border-[#E3EBE5] dark:border-[#1F6B3E] text-sm font-semibold rounded-xl text-[#1B2B20] dark:text-[#F2FAF5] bg-white dark:bg-[#14432A] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/60 focus:outline-none transition">
                                <div class="w-7 h-7 rounded-full bg-[#DFF2E6] text-[#14432A] flex items-center justify-center text-xs font-bold border border-[#BFE3CC]">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="max-w-[120px] truncate" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                                <svg class="h-4 w-4 text-[#5E6B60] dark:text-[#BFE3CC]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-2 border-b border-[#E3EBE5] dark:border-[#1F6B3E]">
                                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Masuk sebagai</p>
                                <p class="text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-xs font-medium text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/50 transition" wire:navigate>
                                Profil Pengguna
                            </a>

                            <a href="{{ route('customer.orders') }}" class="block px-4 py-2 text-xs font-medium text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/50 transition" wire:navigate>
                                Pesanan Jasa Saya
                            </a>

                            <a href="{{ route('customer.product-orders') }}" class="block px-4 py-2 text-xs font-medium text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/50 transition" wire:navigate>
                                Pesanan Material Saya
                            </a>

                            <div class="border-t border-[#E3EBE5] dark:border-[#1F6B3E]"></div>

                            <button wire:click="logout" class="w-full text-start px-4 py-2 text-xs font-bold text-[#B3402F] hover:bg-[#FDF2F0] dark:hover:bg-[#B3402F]/20 transition">
                                Keluar (Log out)
                            </button>
                        </x-slot>
                    </x-ui.dropdown>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm font-bold text-[#14432A] dark:text-[#F2FAF5] hover:text-[#1F6B3E] px-3 py-2 rounded-xl transition">
                            Masuk
                        </a>
                        <x-ui.button :href="route('register')" variant="primary" size="sm" wire:navigate>
                            Daftar
                        </x-ui.button>
                    </div>
                @endauth
            </div>

            <!-- Hamburger Button for Mobile -->
            <div class="-me-2 flex items-center sm:hidden gap-1">
                <button
                    type="button"
                    @click="window.toggleDarkMode(); isDark = document.documentElement.classList.contains('dark')"
                    class="p-2 rounded-xl text-[#5E6B60] dark:text-[#BFE3CC]"
                    aria-label="Ubah mode gelap/terang"
                >
                    <svg x-show="!isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-xl text-[#5E6B60] dark:text-[#BFE3CC] hover:text-[#14432A] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/50 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-[#14432A] border-t border-[#E3EBE5] dark:border-[#1F6B3E] px-4 pt-2 pb-4 space-y-1.5 shadow-lg">
        <a href="{{ route('service.catalog') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/40 transition">
            Cari Jasa & Teknisi
        </a>
        <a href="{{ route('marketplace.index') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/40 transition">
            Marketplace Material
        </a>
        <a href="{{ route('cart.index') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/40 transition">
            Keranjang Belanja Material
        </a>
        @auth
            @if (auth()->user()->isTeknisi())
                <a href="{{ route('technician.dashboard') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/40 transition">
                    Dasbor Pekerjaan
                </a>
            @else
                <a href="{{ route('customer.orders') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/40 transition">
                    Pesanan Jasa Saya
                </a>
                <a href="{{ route('customer.product-orders') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/40 transition">
                    Pesanan Material Saya
                </a>
            @endif
            <a href="{{ route('profile') }}" class="block px-3.5 py-2.5 rounded-xl text-sm font-semibold text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] dark:hover:bg-[#0F2A1A]/40 transition">
                Profil Pengguna
            </a>
            <button wire:click="logout" class="block w-full text-left px-3.5 py-2.5 rounded-xl text-sm font-bold text-[#B3402F] hover:bg-[#FDF2F0] dark:hover:bg-[#B3402F]/20 transition">
                Keluar
            </button>
        @else
            <div class="pt-2 flex flex-col gap-2">
                <a href="{{ route('login') }}" class="block text-center py-2.5 rounded-xl text-sm font-bold border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="block text-center py-2.5 rounded-xl text-sm font-bold bg-[#14432A] text-white hover:bg-[#1F6B3E] transition">
                    Daftar Akun Baru
                </a>
            </div>
        @endauth
    </div>
</nav>
