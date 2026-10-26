@props([
    'variant' => 'primary', // primary, secondary, ghost, danger, outline
    'size' => 'md',        // sm, md, lg
    'type' => 'button',
    'href' => null,
    'disabled' => false,
    'loading' => false,
    'icon' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-xl transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed btn-press-effect touch-target select-none';

    $sizeClasses = [
        'sm' => 'text-xs px-3 py-1.5 gap-1.5 min-h-[36px]',
        'md' => 'text-sm px-4 py-2.5 gap-2 min-h-[44px]',
        'lg' => 'text-base px-6 py-3.5 gap-2.5 min-h-[50px]',
    ][$size] ?? 'text-sm px-4 py-2.5 gap-2 min-h-[44px]';

    $variantClasses = [
        'primary' => 'bg-[#14432A] hover:bg-[#1F6B3E] active:bg-[#0F2A1A] text-white shadow-sm shadow-[#14432A]/20 focus:ring-[#2E8B57]',
        'secondary' => 'bg-[#F2FAF5] hover:bg-[#DFF2E6] text-[#14432A] border border-[#BFE3CC] focus:ring-[#2E8B57] dark:bg-[#14432A]/40 dark:text-[#F2FAF5] dark:border-[#1F6B3E]',
        'outline' => 'bg-transparent hover:bg-[#F2FAF5] text-[#14432A] border border-[#14432A] focus:ring-[#2E8B57] dark:text-[#DFF2E6] dark:border-[#BFE3CC] dark:hover:bg-[#14432A]/50',
        'ghost' => 'bg-transparent hover:bg-[#F2FAF5] text-[#14432A] focus:ring-[#2E8B57] dark:text-[#DFF2E6] dark:hover:bg-[#14432A]/40',
        'danger' => 'bg-[#B3402F] hover:bg-[#963425] active:bg-[#7D2A1E] text-white shadow-sm focus:ring-[#B3402F]',
    ][$variant] ?? 'bg-[#14432A] hover:bg-[#1F6B3E] text-white';

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <span class="shrink-0">{!! $icon !!}</span>
        @endif
        <span>{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        <svg wire:loading {{ $attributes->wire('click')->value() ? '' : 'style=display:none' }} class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        @if ($icon)
            <span class="shrink-0" wire:loading.remove>{{ $icon }}</span>
        @endif
        <span>{{ $slot }}</span>
    </button>
@endif
