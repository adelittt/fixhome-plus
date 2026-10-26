@props([
    'title' => 'Belum ada data',
    'description' => 'Data yang Anda cari tidak ditemukan atau belum tersedia.',
    'icon' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center p-8 sm:p-12 text-center rounded-2xl border-2 border-dashed border-[#E3EBE5] dark:border-[#1F6B3E] bg-white/60 dark:bg-[#14432A]/20']) }}>
    <div class="w-16 h-16 rounded-full bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 flex items-center justify-center text-[#1F6B3E] dark:text-[#3FAE6D] mb-4 shadow-sm">
        @if ($icon)
            {!! $icon !!}
        @else
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
        @endif
    </div>

    <h3 class="text-base font-bold text-[#1B2B20] dark:text-[#F2FAF5] mb-1.5">{{ $title }}</h3>
    <p class="text-xs sm:text-sm text-[#5E6B60] dark:text-[#BFE3CC] max-w-sm mb-5 leading-relaxed">{{ $description }}</p>

    @if ($slot->isNotEmpty())
        <div>
            {{ $slot }}
        </div>
    @endif
</div>
