@props([
    'id' => null,
    'name',
    'price',
    'storeName',
    'image' => null,
    'category' => null,
    'stock' => null,
    'detailUrl' => '#',
    'addToCartAction' => null,
])

<div class="bg-white dark:bg-[#14432A]/30 border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl shadow-card card-hover-effect overflow-hidden flex flex-col group">
    <!-- Image & Badge Container -->
    <a href="{{ $detailUrl }}" class="relative block bg-[#F2FAF5] dark:bg-[#14432A]/60 aspect-square overflow-hidden" wire:navigate>
        @if ($image)
            <img
                src="{{ $image }}"
                alt="{{ $name }}"
                loading="lazy"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
        @else
            <div class="w-full h-full flex flex-col items-center justify-center text-[#5E6B60] dark:text-[#BFE3CC]">
                <svg class="w-12 h-12 mb-2 text-[#2E8B57]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span class="text-[11px] font-medium">Material Bangunan</span>
            </div>
        @endif

        @if ($category)
            <span class="absolute top-2.5 left-2.5 bg-white/90 dark:bg-[#0F2A1A]/90 backdrop-blur-sm text-[#14432A] dark:text-[#F2FAF5] text-[10px] font-bold px-2 py-0.5 rounded-full border border-[#E3EBE5] dark:border-[#1F6B3E]">
                {{ $category }}
            </span>
        @endif

        @if ($stock !== null && $stock <= 5 && $stock > 0)
            <span class="absolute top-2.5 right-2.5 bg-[#FEF8E7] text-[#9A6F14] text-[10px] font-bold px-2 py-0.5 rounded-full border border-[#F5DE9B]">
                Sisa {{ $stock }}
            </span>
        @elseif ($stock === 0)
            <span class="absolute top-2.5 right-2.5 bg-[#FDF2F0] text-[#B3402F] text-[10px] font-bold px-2 py-0.5 rounded-full border border-[#F5B8B0]">
                Habis
            </span>
        @endif
    </a>

    <!-- Info Container -->
    <div class="p-4 flex-1 flex flex-col justify-between">
        <div>
            <!-- Store -->
            <div class="flex items-center gap-1.5 text-xs text-[#5E6B60] dark:text-[#BFE3CC] mb-1.5">
                <svg class="w-3.5 h-3.5 text-[#2E8B57] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span class="truncate font-medium">{{ $storeName }}</span>
            </div>

            <!-- Name -->
            <a href="{{ $detailUrl }}" class="block text-sm font-bold text-[#1B2B20] dark:text-[#F2FAF5] hover:text-[#1F6B3E] dark:hover:text-[#3FAE6D] line-clamp-2 leading-snug mb-2" wire:navigate>
                {{ $name }}
            </a>
        </div>

        <div>
            <!-- Price -->
            <div class="mb-3">
                <p class="text-base font-extrabold text-[#14432A] dark:text-[#3FAE6D] tracking-tight">
                    Rp{{ number_format($price, 0, ',', '.') }}
                </p>
            </div>

            <!-- Actions -->
            @if ($addToCartAction)
                <button
                    type="button"
                    wire:click="{{ $addToCartAction }}"
                    @if ($stock === 0) disabled @endif
                    class="w-full flex items-center justify-center gap-2 py-2 px-3 text-xs font-bold rounded-xl bg-[#F2FAF5] hover:bg-[#14432A] text-[#14432A] hover:text-white border border-[#BFE3CC] hover:border-[#14432A] transition duration-200 btn-press-effect disabled:opacity-40 disabled:cursor-not-allowed dark:bg-[#14432A]/50 dark:text-[#F2FAF5] dark:border-[#1F6B3E] dark:hover:bg-[#14432A]"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span>+ Keranjang</span>
                </button>
            @endif
        </div>
    </div>
</div>
