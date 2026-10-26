<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Isi cepat kredensial demo untuk pengujian role.
     */
    public function fillDemo(string $role): void
    {
        match ($role) {
            'pelanggan' => [
                $this->form->email = 'pelanggan@fixhome.test',
                $this->form->password = 'password',
            ],
            'teknisi' => [
                $this->form->email = 'budi.teknisi@fixhome.test',
                $this->form->password = 'password',
            ],
            'mitra_toko' => [
                $this->form->email = 'toko.jayaabadi@fixhome.test',
                $this->form->password = 'password',
            ],
            'admin' => [
                $this->form->email = 'admin@fixhome.test',
                $this->form->password = 'password',
            ],
            default => null,
        };
    }

    /**
     * Autentikasi dan pengalihan cerdas satu pintu sesuai peran pengguna.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();

        $defaultDestination = match ($user?->role) {
            \App\Enums\UserRole::ADMIN => '/admin',
            \App\Enums\UserRole::MITRA_TOKO => '/toko',
            \App\Enums\UserRole::TEKNISI => route('technician.dashboard', absolute: false),
            default => route('dashboard', absolute: false),
        };

        $this->redirectIntended(default: $defaultDestination, navigate: false);
    }
}; ?>

<div class="bg-white rounded-3xl shadow-xl shadow-emerald-950/5 border border-emerald-900/10 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[580px]">
    <!-- KOLOM KIRI: Brand Hero Banner (Desktop) -->
    <div class="lg:col-span-5 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-950 p-8 sm:p-10 text-white flex flex-col justify-between relative overflow-hidden">
        <!-- Pattern Hiasan Geometris -->
        <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-emerald-700/30 blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-56 h-56 rounded-full bg-amber-400/20 blur-2xl pointer-events-none"></div>

        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-800/80 border border-emerald-700/60 text-emerald-200 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                FixHome Plus Ecosystem
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-snug">
                Solusi Perbaikan Rumah &amp; Marketplace Material
            </h2>
            <p class="text-emerald-100/80 text-sm mt-3 leading-relaxed">
                Pesan teknisi ahli berlisensi, konsultasikan kebutuhan perbaikan, dan beli material bangunan terdekat dalam satu ekosistem terpadu.
            </p>

            <!-- 3 Keunggulan Utama -->
            <div class="mt-8 space-y-4">
                <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-amber-300 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Teknisi Terverifikasi</h4>
                        <p class="text-xs text-emerald-100/70 mt-0.5">Semua teknisi melalui verifikasi dokumen &amp; sertifikasi BNSP oleh tim admin.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-amber-300 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Marketplace Multi-Toko</h4>
                        <p class="text-xs text-emerald-100/70 mt-0.5">Beli kebutuhan material langsung dari toko bangunan fisik terdekat.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3.5">
                    <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-amber-300 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Kurir Instan &amp; Cepat</h4>
                        <p class="text-xs text-emerald-100/70 mt-0.5">Pengiriman cepat ke lokasi pengerjaan dengan pelacakan status transparan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-emerald-800/80 mt-6 flex items-center justify-between text-[11px] text-emerald-200/80">
            <span>Standar Keamanan WCAG AA</span>
            <span>Versi 2.0 (2026)</span>
        </div>
    </div>

    <!-- KOLOM KANAN: Formulir Login -->
    <div class="lg:col-span-7 p-8 sm:p-12 flex flex-col justify-between bg-white">
        <div>
            <!-- Header Judul -->
            <div class="mb-6">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest block mb-1">
                    Portal Masuk Terpadu
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Masuk ke Akun Anda
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Satu pintu masuk untuk Pelanggan, Teknisi, Mitra Toko, dan Admin FixHome Plus.
                </p>
            </div>

            <!-- Session Status Alert -->
            <x-auth-session-status class="mb-5" :status="session('status')" />

            <!-- Quick Demo Credentials Selector -->
            <div class="mb-6 p-3.5 rounded-2xl bg-[#FAF6EA] border border-amber-200/80">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Uji Coba Akun Demo (Klik untuk Isi Cepat):
                    </span>
                    <span class="text-[10px] text-slate-500">Password: password</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" wire:click="fillDemo('pelanggan')" class="px-2.5 py-1.5 rounded-xl bg-white border border-emerald-300/80 text-[11px] font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition shadow-2xs text-center flex items-center justify-center gap-1">
                        <span>👤 Pelanggan</span>
                    </button>
                    <button type="button" wire:click="fillDemo('teknisi')" class="px-2.5 py-1.5 rounded-xl bg-white border border-blue-300/80 text-[11px] font-semibold text-slate-700 hover:bg-blue-50 hover:text-blue-800 transition shadow-2xs text-center flex items-center justify-center gap-1">
                        <span>🛠️ Teknisi</span>
                    </button>
                    <button type="button" wire:click="fillDemo('mitra_toko')" class="px-2.5 py-1.5 rounded-xl bg-white border border-amber-300/80 text-[11px] font-semibold text-slate-700 hover:bg-amber-50 hover:text-amber-800 transition shadow-2xs text-center flex items-center justify-center gap-1">
                        <span>🏪 Toko</span>
                    </button>
                    <button type="button" wire:click="fillDemo('admin')" class="px-2.5 py-1.5 rounded-xl bg-white border border-purple-300/80 text-[11px] font-semibold text-slate-700 hover:bg-purple-50 hover:text-purple-800 transition shadow-2xs text-center flex items-center justify-center gap-1">
                        <span>🛡️ Admin</span>
                    </button>
                </div>
            </div>

            <!-- Formulir Login -->
            <form wire:submit="login" class="space-y-4">
                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="form.email"
                            id="email"
                            type="email"
                            name="email"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="nama@email.com"
                            class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition outline-none"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('form.email')" class="mt-1.5" />
                </div>

                <!-- Input Password dengan Toggle Intuitif -->
                <div x-data="{ show: false }">
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Kata Sandi
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-xs font-semibold text-emerald-700 hover:text-emerald-900 transition" href="{{ route('password.request') }}" wire:navigate>
                                Lupa kata sandi?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="form.password"
                            id="password"
                            :type="show ? 'text' : 'password'"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="block w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition outline-none"
                        />
                        <button
                            type="button"
                            @click="show = !show"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                            tabindex="-1"
                        >
                            <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('form.password')" class="mt-1.5" />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember" class="inline-flex items-center cursor-pointer">
                        <input
                            wire:model="form.remember"
                            id="remember"
                            type="checkbox"
                            class="rounded-md border-slate-300 text-emerald-700 shadow-2xs focus:ring-emerald-600 focus:ring-offset-0 w-4 h-4 cursor-pointer"
                            name="remember"
                        >
                        <span class="ms-2 text-xs font-medium text-slate-600 select-none">
                            Ingat saya di perangkat ini
                        </span>
                    </label>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-800 hover:bg-emerald-900 active:bg-emerald-950 text-white font-bold text-sm tracking-wide shadow-md shadow-emerald-900/20 hover:shadow-lg hover:shadow-emerald-900/30 transition-all transform active:scale-[0.99] cursor-pointer"
                    >
                        <span>Masuk ke Akun</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tautan Registrasi Akun Baru -->
        <div class="pt-6 mt-6 border-t border-slate-100 text-center text-xs text-slate-600">
            <span>Belum memiliki akun FixHome Plus?</span>
            <a
                href="{{ route('register') }}"
                class="font-bold text-emerald-700 hover:text-emerald-900 hover:underline ms-1 transition"
                wire:navigate
            >
                Daftar Akun Baru Sekarang &rarr;
            </a>
        </div>
    </div>
</div>
