<div class="py-10 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-900 rounded-3xl p-8 sm:p-12 mb-10 text-white shadow-xl shadow-blue-900/10 relative overflow-hidden">
            <div class="relative z-10 max-w-2xl">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/20 backdrop-blur-md mb-4 text-blue-100">
                    <svg class="w-4 h-4 text-emerald-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    Teknisi Ahli Terverifikasi
                </span>
                <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">
                    Solusi Perbaikan Rumah Profesional
                </h1>
                <p class="text-blue-100 text-base sm:text-lg mb-6 leading-relaxed">
                    Temukan teknisi berpengalaman di sekitar Anda dengan tarif transparan dan rekomendasi material langsung dari ahlinya.
                </p>

                <!-- Search Bar -->
                <div class="relative flex items-center bg-white rounded-2xl shadow-lg p-2 text-slate-800">
                    <div class="pl-3 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Cari nama teknisi, keahlian, atau kota (mis. Budi, pipa, Tangerang)..." 
                        class="w-full border-none focus:ring-0 text-sm sm:text-base text-slate-800 placeholder-slate-400 px-3 bg-transparent"
                    />
                </div>
            </div>
        </div>

        <!-- Filter Kategori -->
        <div class="mb-8">
            <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Pilih Kategori Layanan</h2>
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <button 
                    wire:click="selectCategory(null)" 
                    class="px-5 py-2.5 rounded-xl font-medium text-sm transition-all whitespace-nowrap {{ is_null($selectedCategory) ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}"
                >
                    Semua Kategori
                </button>
                @foreach ($categories as $cat)
                    <button 
                        wire:click="selectCategory({{ $cat->id }})" 
                        class="px-5 py-2.5 rounded-xl font-medium text-sm transition-all whitespace-nowrap flex items-center gap-2 {{ $selectedCategory === $cat->id ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-white text-slate-700 hover:bg-slate-100 border border-slate-200' }}"
                    >
                        <span>{{ $cat->name }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Grid Teknisi -->
        @if ($technicians->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm max-w-lg mx-auto">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Teknisi Tidak Ditemukan</h3>
                <p class="text-sm text-slate-500">Coba gunakan kata kunci pencarian lain atau pilih kategori layanan berbeda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($technicians as $tech)
                    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-6 flex flex-col justify-between">
                        <div>
                            <!-- Header Kartu -->
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                                    {{ substr($tech->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <h3 class="text-base font-bold text-slate-900 truncate">
                                            {{ $tech->user->name }}
                                        </h3>
                                        <span class="inline-flex text-blue-600 shrink-0" title="Teknisi Terverifikasi Admin">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        </span>
                                    </div>
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                        {{ $tech->category?->name ?? 'Layanan Rumah' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Bio & Lokasi -->
                            <p class="text-xs text-slate-600 line-clamp-2 mb-4 leading-relaxed">
                                {{ $tech->bio ?? 'Teknisi profesional siap melayani kebutuhan perbaikan rumah Anda.' }}
                            </p>

                            <div class="flex items-center gap-1.5 text-xs text-slate-500 mb-4">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="truncate">{{ $tech->address ?? 'Jabodetabek' }}</span>
                            </div>

                            <!-- Layanan yang Ditawarkan -->
                            @if ($tech->services->isNotEmpty())
                                <div class="bg-slate-50 rounded-2xl p-3 mb-4 border border-slate-100">
                                    <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Layanan Populer</div>
                                    <ul class="space-y-1">
                                        @foreach ($tech->services->take(2) as $srv)
                                            <li class="flex items-center justify-between text-xs">
                                                <span class="text-slate-700 truncate pr-2">• {{ $srv->name }}</span>
                                                <span class="font-medium text-slate-900 shrink-0">Rp{{ number_format($srv->price, 0, ',', '.') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Footer Tarif & Aksi -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-4">
                            <div>
                                <div class="text-[11px] text-slate-400">Tarif Dasar Jasa</div>
                                <div class="text-lg font-black text-blue-600">
                                    Rp{{ number_format($tech->base_rate, 0, ',', '.') }}
                                </div>
                            </div>
                            <a 
                                href="{{ route('service.book', $tech->user_id) }}" 
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl font-semibold text-sm bg-blue-600 hover:bg-blue-700 text-white shadow-md shadow-blue-500/20 hover:shadow-lg transition-all"
                            >
                                <span>Pesan Jasa</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
