@props([
    'variant' => 'neutral', // success, warning, danger, info, neutral
    'size' => 'md',        // sm, md
    'dot' => false,
])

@php
    $sizeClasses = [
        'sm' => 'text-[11px] px-2 py-0.5 gap-1 font-semibold',
        'md' => 'text-xs px-2.5 py-1 gap-1.5 font-bold',
    ][$size] ?? 'text-xs px-2.5 py-1 gap-1.5 font-bold';

    $variantClasses = [
        'success' => 'bg-[#DFF2E6] text-[#1F6B3E] border border-[#BFE3CC] dark:bg-[#1F6B3E]/30 dark:text-[#3FAE6D] dark:border-[#1F6B3E]',
        'warning' => 'bg-[#FEF8E7] text-[#9A6F14] border border-[#F5DE9B] dark:bg-[#E0A526]/20 dark:text-[#E0A526] dark:border-[#E0A526]/40',
        'danger' => 'bg-[#FDF2F0] text-[#B3402F] border border-[#F5B8B0] dark:bg-[#B3402F]/20 dark:text-[#F5B8B0] dark:border-[#B3402F]/40',
        'info' => 'bg-[#EDF3F8] text-[#4A6B8A] border border-[#BCD0E2] dark:bg-[#4A6B8A]/20 dark:text-[#BCD0E2] dark:border-[#4A6B8A]/40',
        'neutral' => 'bg-[#F2FAF5] text-[#5E6B60] border border-[#E3EBE5] dark:bg-[#14432A]/30 dark:text-[#BFE3CC] dark:border-[#1F6B3E]',
    ][$variant] ?? 'bg-[#F2FAF5] text-[#5E6B60] border border-[#E3EBE5]';

    $dotColors = [
        'success' => 'bg-[#2E8B57]',
        'warning' => 'bg-[#E0A526]',
        'danger' => 'bg-[#B3402F]',
        'info' => 'bg-[#4A6B8A]',
        'neutral' => 'bg-[#5E6B60]',
    ][$variant] ?? 'bg-[#5E6B60]';
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center rounded-full transition {$sizeClasses} {$variantClasses}"
]) }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors }} shrink-0"></span>
    @endif
    <span>{{ $slot }}</span>
</span>
