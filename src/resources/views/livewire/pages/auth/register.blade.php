<?php

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Store;
use App\Models\TechnicianProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $role = 'pelanggan'; // pelanggan, teknisi, mitra_toko
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['nullable', 'string', 'in:pelanggan,teknisi,mitra_toko'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $userRole = match ($this->role) {
            'teknisi' => UserRole::TEKNISI,
            'mitra_toko' => UserRole::MITRA_TOKO,
            default => UserRole::PELANGGAN,
        };

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: '0812' . rand(10000000, 99999999),
            'role' => $userRole,
            'status' => 'aktif',
            'password' => Hash::make($this->password),
        ]);

        // Buat profil terkait jika mendaftar sebagai teknisi atau toko
        if ($userRole === UserRole::TEKNISI) {
            TechnicianProfile::create([
                'user_id' => $user->id,
                'bio' => 'Mitra teknisi terdaftar di FixHome Plus.',
                'base_rate' => 75000,
                'verification_status' => VerificationStatus::MENUNGGU,
                'is_available' => true,
            ]);
        } elseif ($userRole === UserRole::MITRA_TOKO) {
            Store::create([
                'user_id' => $user->id,
                'name' => 'Toko ' . $this->name,
                'slug' => Str::slug('toko-' . $this->name . '-' . Str::random(5)),
                'phone' => $this->phone ?: '0812' . rand(10000000, 99999999),
                'verification_status' => VerificationStatus::MENUNGGU,
                'is_active' => true,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="bg-white rounded-3xl shadow-xl shadow-emerald-950/5 border border-emerald-900/10 overflow-hidden grid grid-cols-1 lg:grid-cols-12 min-h-[640px]">
    <!-- KOLOM KIRI: Brand Hero Banner -->
    <div class="lg:col-span-5 bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-950 p-8 sm:p-10 text-white flex flex-col justify-between relative overflow-hidden">
        <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-emerald-700/30 blur-2xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-56 h-56 rounded-full bg-amber-400/20 blur-2xl pointer-events-none"></div>

        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-800/80 border border-emerald-700/60 text-emerald-200 text-xs font-semibold mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Bergabung Bersama Kami
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-snug">
                Daftar &amp; Nikmati Kemudahan Perbaikan Rumah
            </h2>
            <p class="text-emerald-100/80 text-sm mt-3 leading-relaxed">
                Pilih peran Anda dalam platform FixHome Plus. Baik sebagai pelanggan, teknisi, maupun pemilik toko bahan bangunan.
            </p>

            <div class="mt-8 space-y-4">
                <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-start gap-3">
                    <span class="text-2xl">🏠</span>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Sebagai Pelanggan</h4>
                        <p class="text-xs text-emerald-100/70 mt-0.5">Pesan jasa teknisi terdekat dengan hitungan jarak akurat dan belanja material bangunan terpadu.</p>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-start gap-3">
                    <span class="text-2xl">🛠️</span>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Sebagai Mitra Teknisi</h4>
                        <p class="text-xs text-emerald-100/70 mt-0.5">Dapatkan pesanan perbaikan rumah rutin dan nikmati komisi jasa transparan 95% untuk Anda.</p>
                    </div>
                </div>

                <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 flex items-start gap-3">
                    <span class="text-2xl">🏪</span>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Sebagai Mitra Toko</h4>
                        <p class="text-xs text-emerald-100/70 mt-0.5">Jangkau pembeli di sekitar lokasi fisik toko Anda dengan integrasi kurir instan otomatis.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-8 border-t border-emerald-800/80 mt-6 flex items-center justify-between text-[11px] text-emerald-200/80">
            <span>Privasi Data Aman Terlindungi</span>
            <span>FixHome Plus &copy; {{ date('Y') }}</span>
        </div>
    </div>

    <!-- KOLOM KANAN: Formulir Registrasi -->
    <div class="lg:col-span-7 p-8 sm:p-12 flex flex-col justify-between bg-white">
        <div>
            <!-- Header Judul -->
            <div class="mb-5">
                <span class="text-xs font-bold text-emerald-700 uppercase tracking-widest block mb-1">
                    Pendaftaran Anggota
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Buat Akun Baru
                </h1>
                <p class="text-sm text-slate-500 mt-1">
                    Lengkapi data di bawah ini untuk memulai akses penuh ke layanan FixHome Plus.
                </p>
            </div>

            <!-- Form -->
            <form wire:submit="register" class="space-y-4">
                <!-- Pemilihan Peran Akun -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Pilih Peran Akun
                    </label>
                    <div class="grid grid-cols-3 gap-2.5">
                        <label class="relative flex flex-col items-center justify-center p-3 rounded-2xl border-2 cursor-pointer transition-all text-center select-none
                            {{ $role === 'pelanggan' ? 'border-emerald-600 bg-emerald-50/70 text-emerald-950 font-bold shadow-2xs' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-100/60 text-slate-700' }}">
                            <input type="radio" wire:model.live="role" value="pelanggan" class="sr-only" />
                            <span class="text-2xl mb-1">🏠</span>
                            <span class="text-xs">Pelanggan</span>
                            <span class="text-[10px] text-slate-500 mt-0.5">Pesan Jasa</span>
                        </label>

                        <label class="relative flex flex-col items-center justify-center p-3 rounded-2xl border-2 cursor-pointer transition-all text-center select-none
                            {{ $role === 'teknisi' ? 'border-emerald-600 bg-emerald-50/70 text-emerald-950 font-bold shadow-2xs' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-100/60 text-slate-700' }}">
                            <input type="radio" wire:model.live="role" value="teknisi" class="sr-only" />
                            <span class="text-2xl mb-1">🛠️</span>
                            <span class="text-xs">Teknisi</span>
                            <span class="text-[10px] text-slate-500 mt-0.5">Terima Kerja</span>
                        </label>

                        <label class="relative flex flex-col items-center justify-center p-3 rounded-2xl border-2 cursor-pointer transition-all text-center select-none
                            {{ $role === 'mitra_toko' ? 'border-emerald-600 bg-emerald-50/70 text-emerald-950 font-bold shadow-2xs' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-100/60 text-slate-700' }}">
                            <input type="radio" wire:model.live="role" value="mitra_toko" class="sr-only" />
                            <span class="text-2xl mb-1">🏪</span>
                            <span class="text-xs">Mitra Toko</span>
                            <span class="text-[10px] text-slate-500 mt-0.5">Jual Material</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('role')" class="mt-1.5" />
                </div>

                <!-- Input Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        {{ $role === 'mitra_toko' ? 'Nama Pemilik / Mitra Toko' : 'Nama Lengkap' }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <input
                            wire:model="name"
                            id="name"
                            type="text"
                            name="name"
                            required
                            autofocus
                            placeholder="Contoh: Hendra Wijaya"
                            class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition outline-none"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
                </div>

                <!-- Dua Kolom: Email & Nomor WhatsApp -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
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
                                wire:model="email"
                                id="email"
                                type="email"
                                name="email"
                                required
                                placeholder="nama@email.com"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition outline-none"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5" />
                    </div>

                    <!-- Input Telepon / WA -->
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor WhatsApp / HP
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <input
                                wire:model="phone"
                                id="phone"
                                type="text"
                                name="phone"
                                required
                                placeholder="081234567890"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition outline-none"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('phone')" class="mt-1.5" />
                    </div>
                </div>

                <!-- Dua Kolom: Password & Confirm Password -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5" x-data="{ show: false }">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input
                                wire:model="password"
                                id="password"
                                :type="show ? 'text' : 'password'"
                                name="password"
                                required
                                placeholder="Min. 8 karakter"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition outline-none"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                Ulangi Sandi
                            </label>
                            <button
                                type="button"
                                @click="show = !show"
                                class="text-[11px] font-semibold text-emerald-700 hover:text-emerald-900 focus:outline-none"
                                tabindex="-1"
                            >
                                <span x-text="show ? 'Sembunyikan' : 'Lihat Sandi'"></span>
                            </button>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <input
                                wire:model="password_confirmation"
                                id="password_confirmation"
                                :type="show ? 'text' : 'password'"
                                name="password_confirmation"
                                required
                                placeholder="Ulangi kata sandi"
                                class="block w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder:text-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 transition outline-none"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5" />
                    </div>
                </div>

                <!-- Tombol Submit -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-800 hover:bg-emerald-900 active:bg-emerald-950 text-white font-bold text-sm tracking-wide shadow-md shadow-emerald-900/20 hover:shadow-lg hover:shadow-emerald-900/30 transition-all transform active:scale-[0.99] cursor-pointer"
                    >
                        <span>Daftar Akun FixHome Plus</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Tautan Masuk -->
        <div class="pt-6 mt-6 border-t border-slate-100 text-center text-xs text-slate-600">
            <span>Sudah memiliki akun FixHome Plus?</span>
            <a
                href="{{ route('login') }}"
                class="font-bold text-emerald-700 hover:text-emerald-900 hover:underline ms-1 transition"
                wire:navigate
            >
                Masuk ke Akun Anda di Sini &rarr;
            </a>
        </div>
    </div>
</div>
