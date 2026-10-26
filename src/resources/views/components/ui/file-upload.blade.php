@props([
    'label' => null,
    'id' => null,
    'name' => null,
    'accept' => 'image/*',
    'error' => null,
    'hint' => 'PNG, JPG, WEBP hingga 5MB',
    'required' => false,
])

@php
    $uploadId = $id ?? ($name ?? 'upload-' . md5(uniqid(mt_rand(), true)));
    $hasError = !empty($error);
@endphp

<div class="w-full">
    @if ($label)
        <label class="block text-sm font-semibold text-[#1B2B20] dark:text-[#F2FAF5] mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-[#B3402F]" title="Wajib diisi">*</span>
            @endif
        </label>
    @endif

    <div
        x-data="{ isDragging: false }"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop="isDragging = false"
        :class="{ 'border-[#2E8B57] bg-[#F2FAF5] dark:bg-[#14432A]/50': isDragging }"
        class="relative flex flex-col items-center justify-center p-6 border-2 border-dashed rounded-2xl transition duration-150 {{ $hasError ? 'border-[#B3402F] bg-[#FDF2F0]/30' : 'border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/20 hover:border-[#BFE3CC]' }}"
    >
        <div class="w-12 h-12 rounded-full bg-[#DFF2E6] dark:bg-[#1F6B3E]/40 flex items-center justify-center text-[#14432A] dark:text-[#BFE3CC] mb-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>

        <p class="text-sm font-medium text-[#1B2B20] dark:text-[#F2FAF5] text-center mb-1">
            <label for="{{ $uploadId }}" class="cursor-pointer font-bold text-[#1F6B3E] dark:text-[#3FAE6D] hover:underline">
                Pilih berkas
            </label>
            atau seret ke sini
        </p>

        @if ($hint)
            <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]/80 text-center">{{ $hint }}</p>
        @endif

        <input
            id="{{ $uploadId }}"
            name="{{ $name }}"
            type="file"
            accept="{{ $accept }}"
            class="sr-only"
            {{ $attributes }}
        />
    </div>

    @if ($hasError)
        <p class="mt-1.5 text-xs font-medium text-[#B3402F] flex items-center gap-1">
            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span>{{ $error }}</span>
        </p>
    @endif
</div>
