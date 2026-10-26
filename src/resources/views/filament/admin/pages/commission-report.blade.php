<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Banner Kepatuhan Aturan Bisnis AB-005 -->
        <div class="rounded-xl border-2 border-emerald-600 bg-emerald-50 dark:bg-emerald-950/40 p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-white">
                    <x-heroicon-m-shield-check class="h-5 w-5" />
                </div>
                <div class="space-y-1">
                    <h3 class="text-sm font-bold text-emerald-900 dark:text-emerald-200">
                        Prinsip Kepatuhan AB-005 &amp; K-5: Laporan Komisi Terpisah dan Transparan
                    </h3>
                    <p class="text-xs leading-relaxed text-emerald-800 dark:text-emerald-300">
                        Komisi Jasa <strong>(5%)</strong> dan Komisi Marketplace <strong>(2%)</strong> disimpan pada dua tabel terpisah (<code class="rounded bg-emerald-100 dark:bg-emerald-900 px-1 py-0.5 text-xs font-mono font-semibold">service_commissions</code> dan <code class="rounded bg-emerald-100 dark:bg-emerald-900 px-1 py-0.5 text-xs font-mono font-semibold">marketplace_commissions</code>). Sesuai aturan bisnis, keduanya <strong>dilarang keras dicampur</strong> dalam satu kalkulasi maupun satu catatan akuntansi. Biaya pengiriman kurir dicatat terpisah dan tidak menjadi dasar komisi (AB-004).
                    </p>
                </div>
            </div>
        </div>

        <!-- KPI Summary Cards (4 Cards Terpisah Sesuai PRD) -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Card 1: Komisi Jasa Platform 5% -->
            <div class="rounded-xl border border-emerald-200 bg-white p-5 shadow-xs dark:border-emerald-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Komisi Jasa (5%)</span>
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">Platform</span>
                </div>
                <p class="mt-2 text-2xl font-black text-emerald-700 dark:text-emerald-400">
                    Rp{{ number_format($stats['totalServiceCommission'], 0, ',', '.') }}
                </p>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>Dari {{ $stats['countService'] }} order selesai</span>
                    <span>Tarif: 5%</span>
                </div>
            </div>

            <!-- Card 2: Hak Penghasilan Teknisi 95% -->
            <div class="rounded-xl border border-emerald-200 bg-white p-5 shadow-xs dark:border-emerald-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hak Teknisi (95%)</span>
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-200">Mitra Jasa</span>
                </div>
                <p class="mt-2 text-2xl font-black text-blue-700 dark:text-blue-400">
                    Rp{{ number_format($stats['totalTechnicianEarning'], 0, ',', '.') }}
                </p>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>Dasar: Rp{{ number_format($stats['totalServiceGross'], 0, ',', '.') }}</span>
                    <span>Porsi: 95%</span>
                </div>
            </div>

            <!-- Card 3: Komisi Marketplace Platform 2% -->
            <div class="rounded-xl border border-amber-200 bg-white p-5 shadow-xs dark:border-amber-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Komisi Produk (2%)</span>
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-200">Platform</span>
                </div>
                <p class="mt-2 text-2xl font-black text-amber-700 dark:text-amber-400">
                    Rp{{ number_format($stats['totalMarketplaceCommission'], 0, ',', '.') }}
                </p>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>Dari {{ $stats['countMarketplace'] }} order toko</span>
                    <span>Tarif: 2% (Murni Produk)</span>
                </div>
            </div>

            <!-- Card 4: Hak Mitra Toko 98% -->
            <div class="rounded-xl border border-amber-200 bg-white p-5 shadow-xs dark:border-amber-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hak Mitra Toko (98%)</span>
                    <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-200">Mitra Toko</span>
                </div>
                <p class="mt-2 text-2xl font-black text-purple-700 dark:text-purple-400">
                    Rp{{ number_format($stats['totalStoreEarning'], 0, ',', '.') }}
                </p>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>Nilai Produk: Rp{{ number_format($stats['totalMarketplaceGross'], 0, ',', '.') }}</span>
                    <span>Porsi: 98%</span>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-xs dark:border-gray-800 dark:bg-gray-900">
            <form wire:submit.prevent="$refresh" class="grid grid-cols-1 gap-4 sm:grid-cols-4 items-end">
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Dari Tanggal</label>
                    <input type="date" wire:model.live="startDate" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:border-emerald-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Sampai Tanggal</label>
                    <input type="date" wire:model.live="endDate" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:border-emerald-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Status Pencatatan</label>
                    <select wire:model.live="statusFilter" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-xs focus:border-emerald-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <option value="semua">Semua Status</option>
                        <option value="tercatat">Tercatat</option>
                        <option value="dicairkan">Dicairkan</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="button" wire:click="resetFilters" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-xs hover:bg-gray-50 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                        Reset Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- GRID DUA TABEL TERPISAH (LAYAR 19 - UI SPECIFICATION) -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- TABEL 1: KOMISI JASA (5%) -->
            <div class="rounded-xl border-2 border-emerald-600 bg-white shadow-sm dark:bg-gray-900 flex flex-col justify-between">
                <div>
                    <div class="flex flex-wrap items-center justify-between border-b border-emerald-100 bg-emerald-50/70 p-4 dark:border-emerald-900/50 dark:bg-emerald-950/20">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-emerald-900 dark:text-emerald-200">
                                    Tabel 1: Komisi Jasa (5%)
                                </h3>
                                <span class="rounded bg-emerald-200 px-1.5 py-0.5 text-[10px] font-mono font-semibold text-emerald-900 dark:bg-emerald-800 dark:text-emerald-100">
                                    service_commissions
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Platform 5% &bull; Teknisi 95% (Dihitung dari dasar tarif jasa)
                            </p>
                        </div>
                        <button type="button" wire:click="exportServiceCommissionsCsv" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-700 px-3 py-1.5 text-xs font-semibold text-white shadow-xs hover:bg-emerald-800 transition">
                            <x-heroicon-m-arrow-down-tray class="h-4 w-4" />
                            Unduh CSV Jasa
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="border-b border-gray-200 bg-gray-50 text-gray-600 dark:border-gray-800 dark:bg-gray-800/50 dark:text-gray-300 uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="px-3 py-2.5">No. Pesanan / Teknisi</th>
                                    <th class="px-3 py-2.5 text-right">Nilai Dasar</th>
                                    <th class="px-3 py-2.5 text-right text-emerald-700 dark:text-emerald-400">Komisi (5%)</th>
                                    <th class="px-3 py-2.5 text-right text-blue-700 dark:text-blue-400">Hak Teknisi (95%)</th>
                                    <th class="px-3 py-2.5 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($serviceCommissions as $sc)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                        <td class="px-3 py-3">
                                            <div class="font-bold text-gray-900 dark:text-white">
                                                {{ $sc->serviceOrder?->order_number ?? 'SRV-#' . $sc->service_order_id }}
                                            </div>
                                            <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                                {{ $sc->technician?->name ?? 'Teknisi #' . $sc->technician_id }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 mt-0.5">
                                                {{ $sc->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-right font-medium text-gray-800 dark:text-gray-200">
                                            Rp{{ number_format($sc->gross_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-3 text-right font-bold text-emerald-700 dark:text-emerald-400">
                                            Rp{{ number_format($sc->platform_commission, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-3 text-right font-bold text-blue-700 dark:text-blue-400">
                                            Rp{{ number_format($sc->technician_earning, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold
                                                @if($sc->status === 'tercatat') bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200
                                                @elseif($sc->status === 'dicairkan') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                {{ ucfirst($sc->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-xs text-gray-400">
                                            Belum ada catatan komisi jasa pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border-t border-emerald-100 bg-emerald-50/50 p-4 dark:border-emerald-900/50 dark:bg-emerald-950/10">
                    <div class="flex items-center justify-between text-xs font-bold text-emerald-950 dark:text-emerald-200">
                        <span>Total Komisi Jasa Platform (5%):</span>
                        <span class="text-sm font-black text-emerald-700 dark:text-emerald-400">
                            Rp{{ number_format($stats['totalServiceCommission'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="mt-1 flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                        <span>Total Penghasilan Teknisi (95%):</span>
                        <span class="font-bold text-blue-700 dark:text-blue-400">
                            Rp{{ number_format($stats['totalTechnicianEarning'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- TABEL 2: KOMISI MARKETPLACE (2%) -->
            <div class="rounded-xl border-2 border-amber-600 bg-white shadow-sm dark:bg-gray-900 flex flex-col justify-between">
                <div>
                    <div class="flex flex-wrap items-center justify-between border-b border-amber-100 bg-amber-50/70 p-4 dark:border-amber-900/50 dark:bg-amber-950/20">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-amber-900 dark:text-amber-200">
                                    Tabel 2: Komisi Marketplace (2%)
                                </h3>
                                <span class="rounded bg-amber-200 px-1.5 py-0.5 text-[10px] font-mono font-semibold text-amber-900 dark:bg-amber-800 dark:text-amber-100">
                                    marketplace_commissions
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Platform 2% &bull; Mitra Toko 98% (Ongkir dikeluarkan dari dasar komisi)
                            </p>
                        </div>
                        <button type="button" wire:click="exportMarketplaceCommissionsCsv" class="inline-flex items-center gap-1.5 rounded-lg bg-amber-700 px-3 py-1.5 text-xs font-semibold text-white shadow-xs hover:bg-amber-800 transition">
                            <x-heroicon-m-arrow-down-tray class="h-4 w-4" />
                            Unduh CSV Marketplace
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="border-b border-gray-200 bg-gray-50 text-gray-600 dark:border-gray-800 dark:bg-gray-800/50 dark:text-gray-300 uppercase tracking-wider font-semibold">
                                <tr>
                                    <th class="px-3 py-2.5">No. Pesanan / Toko</th>
                                    <th class="px-3 py-2.5 text-right">Nilai Produk Murni</th>
                                    <th class="px-3 py-2.5 text-right text-gray-400">Ongkir (Terpisah)</th>
                                    <th class="px-3 py-2.5 text-right text-amber-700 dark:text-amber-400">Komisi (2%)</th>
                                    <th class="px-3 py-2.5 text-right text-purple-700 dark:text-purple-400">Hak Toko (98%)</th>
                                    <th class="px-3 py-2.5 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse($marketplaceCommissions as $mc)
                                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                                        <td class="px-3 py-3">
                                            <div class="font-bold text-gray-900 dark:text-white">
                                                {{ $mc->productOrder?->order_number ?? 'ORD-#' . $mc->product_order_id }}
                                            </div>
                                            <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                                {{ $mc->store?->name ?? 'Toko #' . $mc->store_id }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 mt-0.5">
                                                {{ $mc->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 text-right font-medium text-gray-800 dark:text-gray-200">
                                            Rp{{ number_format($mc->gross_product_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-3 text-right text-[11px] text-gray-400">
                                            Rp{{ number_format($mc->productOrder?->shipping_fee ?? 0, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-3 text-right font-bold text-amber-700 dark:text-amber-400">
                                            Rp{{ number_format($mc->platform_commission, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-3 text-right font-bold text-purple-700 dark:text-purple-400">
                                            Rp{{ number_format($mc->store_earning, 0, ',', '.') }}
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold
                                                @if($mc->status === 'tercatat') bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200
                                                @elseif($mc->status === 'dicairkan') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                {{ ucfirst($mc->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-xs text-gray-400">
                                            Belum ada catatan komisi marketplace pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="border-t border-amber-100 bg-amber-50/50 p-4 dark:border-amber-900/50 dark:bg-amber-950/10">
                    <div class="flex items-center justify-between text-xs font-bold text-amber-950 dark:text-amber-200">
                        <span>Total Komisi Marketplace Platform (2%):</span>
                        <span class="text-sm font-black text-amber-700 dark:text-amber-400">
                            Rp{{ number_format($stats['totalMarketplaceCommission'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="mt-1 flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                        <span>Total Hak Mitra Toko (98%):</span>
                        <span class="font-bold text-purple-700 dark:text-purple-400">
                            Rp{{ number_format($stats['totalStoreEarning'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
