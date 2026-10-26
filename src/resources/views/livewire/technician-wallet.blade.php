<div class="py-10 bg-[#F2FAF5] dark:bg-[#0F2A1A] min-h-screen text-[#1B2B20] dark:text-[#F2FAF5]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Banner Saldo & Dompet Teknisi -->
        <div class="bg-gradient-to-r from-[#14432A] via-[#1F6B3E] to-[#2E8B57] rounded-3xl p-8 sm:p-10 mb-8 text-white shadow-xl flex flex-col lg:flex-row lg:items-center justify-between gap-6 border border-[#2E8B57]/40 relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <div class="flex items-center gap-2 mb-3">
                    <a href="{{ route('technician.dashboard') }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-white/10 hover:bg-white/20 backdrop-blur-md text-[#BFE3CC] transition border border-white/15">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Kembali ke Dasbor Pekerjaan</span>
                    </a>
                    <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-[#E0A526]/20 text-[#F5DE9B] border border-[#E0A526]/30">
                        Dompet Mitra Teknisi
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Kelola Saldo &amp; Penarikan Dana</h1>
                <p class="text-[#E3EBE5] text-xs sm:text-sm mt-1.5 leading-relaxed">
                    Pantau akumulasi bagi hasil jasa bersih 95% Anda dan ajukan transfer pencairan dana ke rekening bank atau e-wallet kapan saja dengan aman.
                </p>
            </div>

            <!-- Tombol Aksi Penarikan Utama -->
            <div class="relative z-10 shrink-0 flex flex-col sm:flex-row lg:flex-col gap-3">
                <button
                    type="button"
                    wire:click="openWithdrawModal"
                    class="inline-flex items-center justify-center gap-2.5 px-6 py-3.5 rounded-2xl bg-[#E0A526] hover:bg-[#C28C19] active:scale-[0.98] text-[#14432A] font-extrabold text-sm shadow-xl shadow-[#0F2A1A]/30 transition-all border border-amber-300/40"
                >
                    <svg class="w-5 h-5 text-[#14432A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Ajukan Penarikan Dana</span>
                </button>
            </div>

            <!-- Background Pattern -->
            <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full bg-[#3FAE6D]/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-64 h-64 rounded-full bg-[#E0A526]/15 blur-3xl pointer-events-none"></div>
        </div>

        <!-- Flash Alert Sukses / Info -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 border border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5] text-xs sm:text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-[#1F6B3E] dark:text-[#3FAE6D] shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <!-- 4 Kartu Metrik Saldo Finansial -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- 1. Saldo Tersedia -->
            <div class="p-6 rounded-3xl bg-gradient-to-br from-white to-[#F2FAF5] dark:from-[#14432A] dark:to-[#0F2A1A] border-2 border-[#3FAE6D]/50 dark:border-[#3FAE6D]/40 shadow-lg shadow-[#3FAE6D]/10 relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#1F6B3E] dark:text-[#BFE3CC]">Saldo Tersedia</span>
                    <span class="p-2 rounded-xl bg-[#DFF2E6] dark:bg-[#1F6B3E] text-[#14432A] dark:text-[#BFE3CC]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-[#14432A] dark:text-white mt-3">
                    Rp{{ number_format($availableBalance, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] mt-1.5 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#3FAE6D]"></span>
                    <span>Siap ditarik ke rekening bank</span>
                </p>
            </div>

            <!-- 2. Sedang Diproses -->
            <div class="p-6 rounded-3xl bg-white dark:bg-[#14432A]/40 border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#5E6B60] dark:text-[#BFE3CC]">Sedang Diproses</span>
                    <span class="p-2 rounded-xl bg-amber-50 dark:bg-amber-950/40 text-[#E0A526]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-[#E0A526] mt-3">
                    Rp{{ number_format($totalPending, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] mt-1.5">Menunggu konfirmasi transfer admin</p>
            </div>

            <!-- 3. Berhasil Dicairkan -->
            <div class="p-6 rounded-3xl bg-white dark:bg-[#14432A]/40 border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#5E6B60] dark:text-[#BFE3CC]">Sudah Dicairkan</span>
                    <span class="p-2 rounded-xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/60 text-[#2E8B57] dark:text-[#3FAE6D]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-[#2E8B57] dark:text-[#3FAE6D] mt-3">
                    Rp{{ number_format($totalSuccess, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] mt-1.5">Dana telah masuk ke rekening Anda</p>
            </div>

            <!-- 4. Total Penghasilan Bersih (95%) -->
            <div class="p-6 rounded-3xl bg-white dark:bg-[#14432A]/40 border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#5E6B60] dark:text-[#BFE3CC]">Total Penghasilan (95%)</span>
                    <span class="p-2 rounded-xl bg-gray-100 dark:bg-white/10 text-[#14432A] dark:text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </span>
                </div>
                <div class="text-2xl sm:text-3xl font-black text-[#14432A] dark:text-white mt-3">
                    Rp{{ number_format($totalEarned, 0, ',', '.') }}
                </div>
                <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] mt-1.5">Akumulasi bagi hasil jasa selesai</p>
            </div>
        </div>

        <!-- Grid 2 Kolom: Riwayat Penarikan Dana & Riwayat Komisi Masuk -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Kolom Kiri (7 Kolom): Riwayat Penarikan Dana -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl p-6 sm:p-7 border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-extrabold text-[#14432A] dark:text-[#F2FAF5]">Riwayat Penarikan Dana</h2>
                            <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] mt-0.5">Daftar permohonan pencairan saldo ke rekening bank</p>
                        </div>
                        <span class="text-xs font-bold text-[#14432A] dark:text-[#BFE3CC] bg-[#F2FAF5] dark:bg-[#14432A] px-3 py-1 rounded-full border border-[#BFE3CC]">
                            {{ $withdrawals->total() }} Transaksi
                        </span>
                    </div>

                    @if ($withdrawals->isEmpty())
                        <div class="text-center py-12 px-4 rounded-2xl bg-[#F2FAF5]/50 dark:bg-[#0F2A1A]/30 border border-dashed border-[#BFE3CC]">
                            <div class="w-12 h-12 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 text-[#2E8B57] dark:text-[#3FAE6D] flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-[#14432A] dark:text-white">Belum Ada Pengajuan Penarikan</h3>
                            <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] mt-1 max-w-sm mx-auto">
                                Saat pekerjaan Anda selesai dan saldo bertambah, Anda dapat mencairkannya kapan saja ke rekening bank.
                            </p>
                            @if ($availableBalance >= 20000)
                                <button
                                    type="button"
                                    wire:click="openWithdrawModal"
                                    class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#2E8B57] hover:bg-[#3FAE6D] text-white text-xs font-bold transition shadow-sm"
                                >
                                    <span>Tarik Saldo Sekarang</span>
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach ($withdrawals as $wd)
                                <div class="p-4 sm:p-5 rounded-2xl border border-[#E3EBE5] dark:border-[#1F6B3E] bg-[#F2FAF5]/40 dark:bg-[#14432A]/20 hover:bg-[#F2FAF5] dark:hover:bg-[#14432A]/40 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono text-xs font-bold text-[#14432A] dark:text-white">#{{ $wd->withdrawal_code }}</span>
                                            
                                            <!-- Badge Status -->
                                            @if ($wd->status === 'berhasil')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-[#DFF2E6] text-[#14432A] border border-[#BFE3CC]">
                                                    ✓ Berhasil Ditransfer
                                                </span>
                                            @elseif ($wd->status === 'diproses')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-700 border border-blue-200">
                                                    ⏳ Sedang Ditransfer
                                                </span>
                                            @elseif ($wd->status === 'ditolak')
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-red-50 text-red-700 border border-red-200">
                                                    ✕ Ditolak
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-50 text-amber-800 border border-amber-200">
                                                    ⏱ Menunggu Transfer
                                                </span>
                                            @endif
                                        </div>

                                        <div class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
                                            Tujuan: <span class="font-semibold text-[#14432A] dark:text-white">{{ $wd->bank_name }}</span> • {{ $wd->account_number }} (a.n {{ $wd->account_holder_name }})
                                        </div>

                                        @if ($wd->notes)
                                            <div class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] italic">
                                                Catatan: "{{ $wd->notes }}"
                                            </div>
                                        @endif

                                        @if ($wd->admin_notes && $wd->status === 'ditolak')
                                            <div class="text-[11px] font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 p-2 rounded-lg border border-red-200 mt-1">
                                                Alasan Ditolak: {{ $wd->admin_notes }} (Saldo dikembalikan)
                                            </div>
                                        @elseif ($wd->admin_notes && $wd->status === 'berhasil')
                                            <div class="text-[11px] font-semibold text-[#2E8B57] dark:text-[#3FAE6D] mt-1">
                                                Info Transfer: {{ $wd->admin_notes }}
                                            </div>
                                        @endif

                                        <div class="text-[10px] text-gray-400 dark:text-gray-500 pt-0.5">
                                            Diajukan: {{ $wd->created_at->translatedFormat('d M Y, H:i') }}
                                        </div>
                                    </div>

                                    <div class="sm:text-right shrink-0">
                                        <div class="text-lg font-black text-[#14432A] dark:text-white">
                                            Rp{{ number_format($wd->amount, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="pt-2">
                                {{ $withdrawals->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Kolom Kanan (5 Kolom): Riwayat Bagi Hasil Jasa Masuk -->
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-white dark:bg-[#14432A]/30 rounded-3xl p-6 sm:p-7 border border-[#E3EBE5] dark:border-[#1F6B3E] shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-extrabold text-[#14432A] dark:text-[#F2FAF5]">Pemasukan Jasa (95%)</h2>
                            <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] mt-0.5">Bagi hasil bersih dari pesanan yang Anda selesaikan</p>
                        </div>
                    </div>

                    @if ($commissions->isEmpty())
                        <div class="text-center py-10 px-4 rounded-2xl bg-[#F2FAF5]/50 dark:bg-[#0F2A1A]/30 border border-dashed border-[#BFE3CC]">
                            <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
                                Belum ada komisi jasa selesai yang tercatat. Selesaikan pesanan pelanggan untuk mulai mengumpulkan saldo!
                            </p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($commissions as $comm)
                                <div class="p-3.5 rounded-2xl border border-[#E3EBE5] dark:border-[#1F6B3E] bg-[#F2FAF5]/30 dark:bg-[#14432A]/20">
                                    <div class="flex items-center justify-between">
                                        <span class="font-mono text-xs font-bold text-[#14432A] dark:text-white">
                                            #{{ $comm->serviceOrder?->order_number ?? 'SRV-' . $comm->service_order_id }}
                                        </span>
                                        <span class="text-xs font-black text-[#2E8B57] dark:text-[#3FAE6D]">
                                            +Rp{{ number_format($comm->technician_earning, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-[11px] text-[#5E6B60] dark:text-[#BFE3CC] mt-1">
                                        <span>Tarif Kotor: Rp{{ number_format($comm->gross_amount, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-gray-400">Platform (5%): -Rp{{ number_format($comm->platform_commission, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">
                                        {{ $comm->created_at->translatedFormat('d M Y, H:i') }}
                                    </div>
                                </div>
                            @endforeach

                            <div class="pt-2">
                                {{ $commissions->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Pengajuan Penarikan Dana -->
    @if ($showWithdrawModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-black/60 backdrop-blur-sm animate-fade-in">
            <div class="relative w-full max-w-lg bg-white dark:bg-[#14432A] rounded-3xl shadow-2xl border border-[#2E8B57]/30 p-6 sm:p-8 overflow-hidden text-[#14432A] dark:text-white">
                
                <!-- Close Button -->
                <button
                    type="button"
                    wire:click="closeWithdrawModal"
                    class="absolute top-5 right-5 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-white/10 dark:hover:bg-white/20 text-gray-500 dark:text-gray-300 flex items-center justify-center transition"
                >
                    ✕
                </button>

                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-2xl bg-[#DFF2E6] dark:bg-[#2E8B57]/40 text-[#2E8B57] dark:text-[#3FAE6D] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold">Pengajuan Penarikan Dana</h3>
                        <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">Transfer saldo dompet ke rekening pribadi Anda</p>
                    </div>
                </div>

                <!-- Info Saldo Tersedia -->
                <div class="p-3.5 rounded-2xl bg-[#F2FAF5] dark:bg-[#0F2A1A]/40 border border-[#BFE3CC] dark:border-[#1F6B3E] mb-5 flex items-center justify-between">
                    <span class="text-xs font-semibold text-[#5E6B60] dark:text-[#BFE3CC]">Saldo Maksimal Tersedia:</span>
                    <span class="text-sm font-black text-[#14432A] dark:text-[#3FAE6D]">Rp{{ number_format($availableBalance, 0, ',', '.') }}</span>
                </div>

                <form wire:submit.prevent="submitWithdrawal" class="space-y-4">
                    <!-- Input Nominal -->
                    <div>
                        <label class="block text-xs font-bold mb-1">Nominal Penarikan (Rp)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center font-bold text-sm text-gray-500">Rp</span>
                            <input
                                type="number"
                                wire:model.live="amount"
                                placeholder="Contoh: 50000"
                                class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E] bg-white dark:bg-[#0F2A1A] text-sm font-bold focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent outline-none transition"
                            />
                        </div>
                        @error('amount') <span class="text-[11px] font-semibold text-red-500 mt-1 block">{{ $message }}</span> @enderror

                        <!-- Quick Amount Buttons -->
                        <div class="flex flex-wrap gap-2 mt-2">
                            <button type="button" wire:click="setQuickAmount(50000)" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-[#F2FAF5] hover:bg-[#DFF2E6] dark:bg-white/10 dark:hover:bg-white/20 border border-[#BFE3CC] transition">
                                Rp50.000
                            </button>
                            <button type="button" wire:click="setQuickAmount(100000)" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-[#F2FAF5] hover:bg-[#DFF2E6] dark:bg-white/10 dark:hover:bg-white/20 border border-[#BFE3CC] transition">
                                Rp100.000
                            </button>
                            <button type="button" wire:click="setQuickAmount(250000)" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-[#F2FAF5] hover:bg-[#DFF2E6] dark:bg-white/10 dark:hover:bg-white/20 border border-[#BFE3CC] transition">
                                Rp250.000
                            </button>
                            <button type="button" wire:click="withdrawAll" class="px-2.5 py-1 text-[11px] font-bold rounded-lg bg-[#2E8B57] hover:bg-[#1F6B3E] text-white transition">
                                Tarik Semua Saldo
                            </button>
                        </div>
                    </div>

                    <!-- Pilih Bank / E-Wallet -->
                    <div>
                        <label class="block text-xs font-bold mb-1">Bank / E-Wallet Tujuan</label>
                        <select
                            wire:model="bank_name"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E] bg-white dark:bg-[#0F2A1A] text-xs font-semibold focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent outline-none transition"
                        >
                            @foreach ($bankOptions as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('bank_name') <span class="text-[11px] font-semibold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor Rekening / E-Wallet -->
                    <div>
                        <label class="block text-xs font-bold mb-1">Nomor Rekening / No. HP E-Wallet</label>
                        <input
                            type="text"
                            wire:model="account_number"
                            placeholder="Contoh: 8271928371 atau 08123456789"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E] bg-white dark:bg-[#0F2A1A] text-xs font-semibold focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent outline-none transition"
                        />
                        @error('account_number') <span class="text-[11px] font-semibold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Pemilik Rekening -->
                    <div>
                        <label class="block text-xs font-bold mb-1">Nama Pemilik Rekening Sesuai Buku Tabungan</label>
                        <input
                            type="text"
                            wire:model="account_holder_name"
                            placeholder="Contoh: Budi Santoso"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E] bg-white dark:bg-[#0F2A1A] text-xs font-semibold focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent outline-none transition"
                        />
                        @error('account_holder_name') <span class="text-[11px] font-semibold text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan Tambahan (Opsional) -->
                    <div>
                        <label class="block text-xs font-bold mb-1">Catatan Tambahan (Opsional)</label>
                        <input
                            type="text"
                            wire:model="notes"
                            placeholder="Pesan untuk admin transfer..."
                            class="w-full px-3.5 py-2 rounded-xl border border-[#BFE3CC] dark:border-[#1F6B3E] bg-white dark:bg-[#0F2A1A] text-xs focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent outline-none transition"
                        />
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-3 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            wire:click="closeWithdrawModal"
                            class="px-5 py-2.5 rounded-xl font-bold text-xs text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white transition"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#2E8B57] hover:bg-[#3FAE6D] active:scale-[0.98] text-white font-bold text-xs shadow-md transition disabled:opacity-50"
                        >
                            <span wire:loading.remove>Kirim Pengajuan</span>
                            <span wire:loading>Memproses...</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    @endif
</div>
