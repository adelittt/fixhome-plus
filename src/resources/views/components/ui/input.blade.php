@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'type' => 'text',
    'placeholder' => '',
    'error' => null,
    'hint' => null,
    'leadingIcon' => null,
    'trailingIcon' => null,
    'required' => false,
])

@php
    $inputId = $id ?? ($name ?? 'input-' . md5(uniqid(mt_rand(), true)));
    $hasError = !empty($error);
@endphp

<div class="w-full">
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-semibold text-[#1B2B20] dark:text-[#F2FAF5] mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-[#B3402F]" title="Wajib diisi">*</span>
            @endif
        </label>
    @endif

    <div class="relative rounded-xl shadow-sm">
        @if ($leadingIcon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-[#5E6B60] dark:text-[#BFE3CC]">
                {{ $leadingIcon }}
            </div>
        @endif

        <input
            id="{{ $inputId }}"
            name="{{ $name }}"
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            @if ($hasError) aria-invalid="true" aria-describedby="{{ $inputId }}-error" @endif
            {{ $attributes->merge([
                'class' => 'w-full text-sm rounded-xl transition duration-150 py-2.5 min-h-[44px] ' .
                    ($leadingIcon ? 'pl-10 ' : 'pl-3.5 ') .
                    ($trailingIcon ? 'pr-10 ' : 'pr-3.5 ') .
                    ($hasError
                        ? 'border-[#B3402F] text-[#B3402F] focus:border-[#B3402F] focus:ring-[#B3402F]/20 dark:bg-[#14432A]/50'
                        : 'border-[#E3EBE5] text-[#1B2B20] bg-white hover:border-[#BFE3CC] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 dark:bg-[#14432A]/30 dark:border-[#1F6B3E] dark:text-[#F2FAF5] dark:focus:border-[#3FAE6D]')
            ]) }}
        />

        @if ($trailingIcon)
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3.5 text-[#5E6B60] dark:text-[#BFE3CC]">
                {{ $trailingIcon }}
            </div>
        @endif
    </div>

    @if ($hasError)
        <p id="{{ $inputId }}-error" class="mt-1.5 text-xs font-medium text-[#B3402F] flex items-center gap-1">
            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span>{{ $error }}</span>
        </p>
    @elseif ($hint)
        <p class="mt-1.5 text-xs text-[#5E6B60] dark:text-[#BFE3CC]/80">{{ $hint }}</p>
    @endif
</div>
