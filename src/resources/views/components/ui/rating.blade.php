@props([
    'rating' => 5,
    'max' => 5,
    'count' => null,
    'interactive' => false,
    'size' => 'sm', // sm, md, lg
])

@php
    $starSizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ][$size] ?? 'w-4 h-4';
@endphp

<div
    @if ($interactive)
        x-data="{ currentRating: {{ $rating }}, hoverRating: 0 }"
    @endif
    {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5']) }}
>
    <div class="flex items-center gap-0.5">
        @for ($i = 1; $i <= $max; $i++)
            @if ($interactive)
                <button
                    type="button"
                    @mouseenter="hoverRating = {{ $i }}"
                    @mouseleave="hoverRating = 0"
                    @click="currentRating = {{ $i }}; $dispatch('rated', {{ $i }})"
                    class="focus:outline-none transition-transform hover:scale-110 p-0.5"
                >
                    <svg
                        :class="(hoverRating ? hoverRating >= {{ $i }} : currentRating >= {{ $i }}) ? 'text-[#E0A526] fill-[#E0A526]' : 'text-[#E3EBE5] dark:text-[#1F6B3E] fill-none'"
                        class="{{ $starSizes }} transition-colors"
                        viewBox="0 0 20 20"
                        stroke="currentColor"
                        stroke-width="1.5"
                    >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </button>
            @else
                <svg
                    class="{{ $starSizes }} {{ $i <= round($rating) ? 'text-[#E0A526] fill-[#E0A526]' : 'text-[#E3EBE5] dark:text-[#1F6B3E] fill-none' }}"
                    viewBox="0 0 20 20"
                    stroke="currentColor"
                    stroke-width="1.5"
                >
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
            @endif
        @endfor
    </div>

    <span class="text-xs font-bold text-[#1B2B20] dark:text-[#F2FAF5]">
        {{ number_format($rating, 1, ',', '.') }}
    </span>

    @if ($count !== null)
        <span class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]">
            ({{ $count }})
        </span>
    @endif
</div>
