@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-xs text-[#1B2B20] dark:text-[#F2FAF5] mb-1']) }}>
    {{ $value ?? $slot }}
</label>
