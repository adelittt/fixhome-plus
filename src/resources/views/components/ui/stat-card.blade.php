@props([
    'title',
    'value',
    'icon' => null,
    'trend' => null,
    'trendType' => 'up', // up, down, neutral
    'subtitle' => null,
])

<div class="bg-white dark:bg-[#14432A]/30 border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl shadow-card p-5 flex items-center gap-4">
    @if ($icon)
        <div class="w-13 h-13 rounded-2xl bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 text-[#14432A] dark:text-[#3FAE6D] flex items-center justify-center shrink-0 border border-[#BFE3CC]/60 shadow-sm">
            {!! $icon !!}
        </div>
    @endif

    <div class="min-w-0 flex-1">
        <p class="text-xs font-semibold text-[#5E6B60] dark:text-[#BFE3CC] uppercase tracking-wider mb-0.5 truncate">
            {{ $title }}
        </p>

        <h3 class="text-2xl font-extrabold text-[#1B2B20] dark:text-[#F2FAF5] tracking-tight">
            {{ $value }}
        </h3>

        @if ($trend || $subtitle)
            <div class="flex items-center gap-2 mt-1">
                @if ($trend)
                    <span class="inline-flex items-center text-xs font-bold {{ $trendType === 'up' ? 'text-[#1F6B3E] dark:text-[#3FAE6D]' : ($trendType === 'down' ? 'text-[#B3402F]' : 'text-[#5E6B60]') }}">
                        @if ($trendType === 'up')
                            <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                        @elseif ($trendType === 'down')
                            <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        @endif
                        {{ $trend }}
                    </span>
                @endif

                @if ($subtitle)
                    <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]/80 truncate">{{ $subtitle }}</span>
                @endif
            </div>
        @endif
    </div>
</div>
