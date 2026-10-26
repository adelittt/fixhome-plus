<div class="bg-white rounded-3xl border border-slate-200/80 p-6 sm:p-8 shadow-sm">
    <div class="pb-4 mb-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
        <div>
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span>Rekomendasi Material oleh Teknisi</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Daftar material dan bahan perbaikan yang disarankan oleh teknisi berdasarkan analisis kerusakan.</p>
        </div>

        @if ($recommendation && $recommendation->revision_count > 0)
            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200 shrink-0">
                Revisi ke-{{ $recommendation->revision_count }}
            </span>
        @endif
    </div>

    <!-- Flash Pesan -->
    @if (session()->has('choice_success'))
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span>{{ session('choice_success') }}</span>
        </div>
    @endif
    @if (session()->has('choice_error'))
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span>{{ session('choice_error') }}</span>
        </div>
    @endif

    @if (! $recommendation || $recommendation->items->isEmpty())
        <!-- Belum Ada Rekomendasi -->
        <div class="bg-slate-50 rounded-2xl p-8 text-center border border-slate-100">
            <div class="w-12 h-12 bg-white rounded-2xl text-slate-400 flex items-center justify-center mx-auto mb-3 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            </div>
            <h4 class="text-sm font-bold text-slate-800 mb-1">Belum Ada Rekomendasi Material</h4>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">Teknisi belum menyusun daftar rekomendasi material untuk pesanan ini. Anda dapat mendiskusikannya melalui tab Live Chat.</p>
        </div>
    @else
        <!-- Daftar Butir Material -->
        <div class="space-y-3 mb-6">
            @foreach ($recommendation->items as $idx => $item)
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center shrink-0">
                                {{ $idx + 1 }}
                            </span>
                            <span class="font-bold text-slate-900 text-sm">{{ $item->material_name }}</span>
                        </div>
                        @if ($item->specification)
                            <div class="text-xs text-slate-600 pl-8">
                                <span class="text-slate-400">Spesifikasi:</span> {{ $item->specification }}
                            </div>
                        @endif
                        @if ($item->notes)
                            <div class="text-[11px] text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md inline-block ml-8">
                                Catatan: {{ $item->notes }}
                            </div>
                        @endif
                    </div>

                    <div class="sm:text-right shrink-0 pl-8 sm:pl-0">
                        <div class="text-xs font-extrabold text-slate-900">
                            {{ $item->quantity }} {{ $item->unit }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($recommendation->notes)
            <div class="bg-blue-50/60 rounded-2xl p-4 border border-blue-100 text-xs text-blue-900 mb-6">
                <strong class="block mb-0.5 text-blue-950 font-semibold">Pesan Khusus Teknisi:</strong>
                {{ $recommendation->notes }}
            </div>
        @endif

        <!-- Pilihan Marketplace Setara (PRD 4.2 & AB-006) -->
        <div class="pt-6 border-t border-slate-100">
            <div class="mb-4">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">
                    Pilihan Pemenuhan Material
                </span>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Pembelian material di FixHome Plus sepenuhnya opsional. Anda dapat membeli langsung melalui marketplace multi-toko kami atau menyediakannya secara mandiri tanpa memengaruhi alur maupun tarif jasa perbaikan.
                </p>
            </div>

            <!-- Status Pilihan Saat Ini -->
            @if ($order->marketplace_choice->value !== 'belum_dipilih')
                <div class="mb-4 p-4 rounded-2xl {{ $order->marketplace_choice->value === 'lihat' ? 'bg-blue-50 border border-blue-200 text-blue-900' : 'bg-slate-100 border border-slate-200 text-slate-800' }} flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div>
                        <span class="font-bold">Status Pilihan Anda:</span>
                        @if ($order->marketplace_choice->value === 'lihat')
                            <span>Menelusuri Marketplace Material FixHome Plus</span>
                        @else
                            <span>Menyediakan Material Mandiri (Lewati Marketplace)</span>
                        @endif
                        <span class="text-[11px] text-slate-500 font-normal block mt-0.5">Dapat diubah selama status jasa belum selesai</span>
                    </div>
                    @if ($order->marketplace_choice->value === 'lihat')
                        <a href="{{ route('marketplace.index', ['pesanan_jasa' => $order->id]) }}" class="px-3.5 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition inline-flex items-center gap-1 shrink-0 self-start sm:self-auto">
                            <span>Buka Marketplace</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @endif
                </div>
            @endif

            <!-- DUA TOMBOL SETARA (PRD 4.2 BUTIR 1) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Tombol 1: Lihat Marketplace -->
                <button 
                    type="button" 
                    wire:click="chooseOption('lihat')" 
                    class="w-full py-3.5 px-6 rounded-2xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 {{ $order->marketplace_choice->value === 'lihat' ? 'bg-blue-700 text-white ring-4 ring-blue-300 shadow-lg shadow-blue-600/30' : 'bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-600/20' }}"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <span>Lihat Marketplace</span>
                </button>

                <!-- Tombol 2: Lewati Marketplace (Setara dalam kontras dan ukuran) -->
                <button 
                    type="button" 
                    wire:click="chooseOption('lewati')" 
                    class="w-full py-3.5 px-6 rounded-2xl font-bold text-sm transition-all duration-200 flex items-center justify-center gap-2 {{ $order->marketplace_choice->value === 'lewati' ? 'bg-slate-800 text-white ring-4 ring-slate-400 shadow-lg shadow-slate-900/30' : 'bg-slate-700 hover:bg-slate-800 text-white shadow-md shadow-slate-700/20' }}"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                    <span>Lewati Marketplace</span>
                </button>
            </div>
        </div>
    @endif
</div>
