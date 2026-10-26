@props([
    'text',
    'position' => 'top',
])

@php
$positionClasses = [
    'top' => 'bottom-full left-1/2 -translate-x-1/2 mb-2',
    'bottom' => 'top-full left-1/2 -translate-x-1/2 mt-2',
    'left' => 'right-full top-1/2 -translate-y-1/2 mr-2',
    'right' => 'left-full top-1/2 -translate-y-1/2 ml-2',
][$position] ?? 'bottom-full left-1/2 -translate-x-1/2 mb-2';
@endphp

<div class="relative inline-flex items-center" x-data="{ show: false }">
    <div @mouseenter="show = true" @mouseleave="show = false" @focus="show = true" @blur="show = false">
        {{ $slot }}
    </div>

    <div
        x-show="show"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        role="tooltip"
        class="absolute z-50 px-2.5 py-1 text-xs font-semibold text-white bg-[#0F2A1A] rounded-lg shadow-md whitespace-nowrap pointer-events-none {{ $positionClasses }}"
        style="display: none;"
    >
        {{ $text }}
    </div>
</div>
