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

<nav x-data="{ open: false }" class="bg-white border-b border-slate-200/80 sticky top-0 z-50 shadow-sm backdrop-blur-md bg-white/95">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-2" wire:navigate>
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-700 to-indigo-600 flex items-center justify-center text-white shadow-md shadow-blue-600/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="font-black text-xl tracking-tight text-slate-900">FixHome<span class="text-blue-600">Plus</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('service.catalog')" :active="request()->routeIs('service.catalog') || request()->routeIs('home')" wire:navigate>
                        {{ __('Cari Jasa & Teknisi') }}
                    </x-nav-link>

                    <x-nav-link :href="route('marketplace.index')" :active="request()->routeIs('marketplace.*')" wire:navigate>
                        {{ __('Marketplace Material') }}
                    </x-nav-link>

                    @auth
                        @if (auth()->user()->isTeknisi())
                            <x-nav-link :href="route('technician.dashboard')" :active="request()->routeIs('technician.dashboard')" wire:navigate>
                                {{ __('Dasbor Pekerjaan') }}
                            </x-nav-link>
                        @else
                            <x-nav-link :href="route('customer.orders')" :active="request()->routeIs('customer.orders') || request()->routeIs('customer.product-orders')" wire:navigate>
                                {{ __('Pesanan Saya') }}
                            </x-nav-link>
                        @endif

                        @if (auth()->user()->isMitraToko())
                            <a href="/toko" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-emerald-600 hover:text-emerald-700">
                                {{ __('Panel Mitra Toko') }}
                            </a>
                        @endif

                        @if (auth()->user()->isAdmin())
                            <a href="/admin" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-indigo-600 hover:text-indigo-700">
                                {{ __('Panel Admin') }}
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Settings / Auth Menu -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">
                @auth
                    <!-- Keranjang Belanja -->
                    <a href="{{ route('cart.index') }}" class="relative p-2 rounded-xl text-slate-500 hover:text-blue-600 hover:bg-slate-50 transition" title="Keranjang Belanja Material" wire:navigate>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        @php
                            $cartCount = \App\Models\CartItem::where('customer_id', auth()->id())->sum('quantity');
                        @endphp
                        @if ($cartCount > 0)
                            <span class="absolute top-0 right-0 w-4 h-4 bg-blue-600 text-white rounded-full text-[10px] font-extrabold flex items-center justify-center">
                                {{ $cartCount > 9 ? '9+' : $cartCount }}
                            </span>
                        @endif
                    </a>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3.5 py-2 border border-slate-200 text-sm font-semibold rounded-2xl text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition">
                                <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                                <svg class="fill-current h-4 w-4 text-slate-400" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile')" wire:navigate>
                                {{ __('Profil Pengguna') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('customer.orders')" wire:navigate>
                                {{ __('Pesanan Jasa Saya') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('customer.product-orders')" wire:navigate>
                                {{ __('Pesanan Material Saya') }}
                            </x-dropdown-link>

                            <button wire:click="logout" class="w-full text-start px-4 py-2 text-sm text-rose-600 hover:bg-rose-50">
                                {{ __('Keluar (Log out)') }}
                            </button>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 hover:text-blue-600 px-3 py-2">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="text-sm font-bold bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl shadow-md shadow-blue-500/20 transition-all">
                            Daftar
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-slate-500 hover:bg-slate-100">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-slate-100 px-4 pt-2 pb-4 space-y-2">
        <a href="{{ route('service.catalog') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">
            Cari Jasa & Teknisi
        </a>
        <a href="{{ route('marketplace.index') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">
            Marketplace Material
        </a>
        <a href="{{ route('cart.index') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">
            Keranjang Belanja Material
        </a>
        @auth
            @if (auth()->user()->isTeknisi())
                <a href="{{ route('technician.dashboard') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">
                    Dasbor Pekerjaan
                </a>
            @else
                <a href="{{ route('customer.orders') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">
                    Pesanan Jasa Saya
                </a>
                <a href="{{ route('customer.product-orders') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">
                    Pesanan Material Saya
                </a>
            @endif
            <button wire:click="logout" class="block w-full text-left px-3 py-2 rounded-xl text-base font-medium text-rose-600 hover:bg-rose-50">
                Keluar
            </button>
        @else
            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-slate-700 hover:bg-slate-50">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-xl text-base font-medium text-blue-600 hover:bg-blue-50 font-bold">
                Daftar Akun Baru
            </a>
        @endauth
    </div>
</nav>
