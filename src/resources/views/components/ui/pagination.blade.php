@props(['paginator'])

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex items-center justify-between py-3">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-xs font-semibold text-[#5E6B60] bg-white dark:bg-[#14432A] border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-xl cursor-not-allowed opacity-50">
                    Sebelumnya
                </span>
            @else
                <button wire:click="previousPage" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-[#14432A] bg-white dark:bg-[#14432A] dark:text-[#F2FAF5] border border-[#BFE3CC] dark:border-[#1F6B3E] rounded-xl hover:bg-[#F2FAF5] active:bg-[#DFF2E6] transition">
                    Sebelumnya
                </button>
            @endif

            @if ($paginator->hasMorePages())
                <button wire:click="nextPage" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-[#14432A] bg-white dark:bg-[#14432A] dark:text-[#F2FAF5] border border-[#BFE3CC] dark:border-[#1F6B3E] rounded-xl hover:bg-[#F2FAF5] active:bg-[#DFF2E6] transition">
                    Berikutnya
                </button>
            @else
                <span class="inline-flex items-center px-4 py-2 text-xs font-semibold text-[#5E6B60] bg-white dark:bg-[#14432A] border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-xl cursor-not-allowed opacity-50">
                    Berikutnya
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
                    Menampilkan
                    <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-bold text-[#1B2B20] dark:text-[#F2FAF5]">{{ $paginator->total() }}</span>
                    hasil
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex rounded-xl shadow-sm space-x-1">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="Sebelumnya" class="inline-flex items-center p-2 rounded-xl border border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A] text-xs font-medium text-[#5E6B60] cursor-not-allowed opacity-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <button wire:click="previousPage" rel="prev" class="inline-flex items-center p-2 rounded-xl border border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A] text-xs font-medium text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <button wire:click="nextPage" rel="next" class="inline-flex items-center p-2 rounded-xl border border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A] text-xs font-medium text-[#1B2B20] dark:text-[#F2FAF5] hover:bg-[#F2FAF5] transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @else
                        <span aria-disabled="true" aria-label="Berikutnya" class="inline-flex items-center p-2 rounded-xl border border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A] text-xs font-medium text-[#5E6B60] cursor-not-allowed opacity-50">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
