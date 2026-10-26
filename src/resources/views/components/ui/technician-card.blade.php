@props([
    'id' => null,
    'name',
    'avatar' => null,
    'skills' => [],
    'rating' => 5.0,
    'reviewCount' => 0,
    'basePrice' => 50000,
    'distance' => null,
    'isAvailable' => true,
    'bookUrl' => '#',
])

<div class="bg-white dark:bg-[#14432A]/30 border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl shadow-card card-hover-effect p-5 flex flex-col justify-between">
    <div>
        <!-- Header: Avatar, Name, Rating & Availability -->
        <div class="flex items-start gap-3.5 mb-4">
            <div class="relative shrink-0">
                @if ($avatar)
                    <img src="{{ $avatar }}" alt="{{ $name }}" class="w-13 h-13 rounded-2xl object-cover border-2 border-[#E3EBE5] dark:border-[#1F6B3E]" />
                @else
                    <div class="w-13 h-13 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 text-[#14432A] dark:text-[#3FAE6D] font-black text-lg flex items-center justify-center border border-[#BFE3CC]">
                        {{ substr($name, 0, 1) }}
                    </div>
                @endif
                <span class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full border-2 border-white dark:border-[#14432A] {{ $isAvailable ? 'bg-[#3FAE6D]' : 'bg-[#B3402F]' }}" title="{{ $isAvailable ? 'Tersedia' : 'Sedang Sibuk' }}"></span>
            </div>

            <div class="min-w-0 flex-1">
                <div class="flex items-center gap-1.5">
                    <h4 class="text-base font-bold text-[#1B2B20] dark:text-[#F2FAF5] truncate">{{ $name }}</h4>
                    <span class="shrink-0 text-[#2E8B57]" title="Teknisi Terverifikasi">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </span>
                </div>

                <div class="flex items-center gap-2 mt-1">
                    <x-ui.rating :rating="$rating" :count="$reviewCount" size="sm" />
                </div>
            </div>
        </div>

        <!-- Skills Chips -->
        <div class="flex flex-wrap gap-1.5 mb-4">
            @foreach ((array) $skills as $skill)
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-lg bg-[#F2FAF5] dark:bg-[#14432A]/60 text-[#14432A] dark:text-[#BFE3CC] border border-[#BFE3CC]/60 dark:border-[#1F6B3E]">
                    {{ $skill }}
                </span>
            @endforeach
        </div>

        <!-- Distance if available -->
        @if ($distance !== null)
            <div class="flex items-center gap-1.5 text-xs text-[#5E6B60] dark:text-[#BFE3CC] mb-3">
                <svg class="w-3.5 h-3.5 text-[#1F6B3E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Estimasi jarak rute: <strong class="text-[#1B2B20] dark:text-[#F2FAF5]">{{ $distance }} km</strong></span>
                @if ($distance >= 10)
                    <span class="text-[10px] text-[#9A6F14] bg-[#FEF8E7] px-1.5 py-0.2 rounded border border-[#F5DE9B]">+Rp10.000</span>
                @endif
            </div>
        @endif
    </div>

    <!-- Pricing & Book Button -->
    <div class="pt-3 border-t border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center justify-between gap-3">
        <div>
            <p class="text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]">Mulai dari</p>
            <p class="text-base font-extrabold text-[#14432A] dark:text-[#3FAE6D]">
                Rp{{ number_format($basePrice, 0, ',', '.') }}
            </p>
        </div>

        <x-ui.button
            :href="$bookUrl"
            variant="primary"
            size="sm"
            wire:navigate
        >
            Pesan Jasa
        </x-ui.button>
    </div>
</div>
