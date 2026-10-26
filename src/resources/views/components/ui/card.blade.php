@props([
    'hoverable' => false,
    'padding' => 'p-6',
    'title' => null,
    'subtitle' => null,
    'headerAction' => null,
    'footer' => null,
])

<div {{ $attributes->merge([
    'class' => 'bg-white dark:bg-[#14432A]/30 border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl shadow-card transition duration-200 overflow-hidden flex flex-col ' .
        ($hoverable ? 'card-hover-effect cursor-pointer ' : '')
]) }}>
    @if ($title || $headerAction)
        <div class="px-6 py-4.5 border-b border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center justify-between gap-4">
            <div>
                @if ($title)
                    <h3 class="text-base font-bold text-[#1B2B20] dark:text-[#F2FAF5] tracking-tight">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC] mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($headerAction)
                <div class="shrink-0">
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padding }} flex-1">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-6 py-4 bg-[#F2FAF5]/60 dark:bg-[#0F2A1A]/40 border-t border-[#E3EBE5] dark:border-[#1F6B3E]">
            {{ $footer }}
        </div>
    @endif
</div>
