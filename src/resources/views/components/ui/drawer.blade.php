@props([
    'name',
    'title' => null,
    'width' => 'max-w-md',
])

<div
    x-data="{ show: false }"
    x-on:open-drawer.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-drawer.window="$event.detail == '{{ $name }}' ? show = false : null"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    role="dialog"
    aria-modal="true"
    class="fixed inset-0 z-50 overflow-hidden"
    style="display: none;"
>
    <!-- Backdrop -->
    <div
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
        @click="show = false"
    ></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <!-- Slide-over panel -->
        <div
            x-show="show"
            x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="w-screen {{ $width }} bg-white dark:bg-[#14432A] shadow-elevated border-l border-[#E3EBE5] dark:border-[#1F6B3E] flex flex-col"
        >
            <!-- Drawer Header -->
            <div class="px-6 py-4.5 border-b border-[#E3EBE5] dark:border-[#1F6B3E] flex items-center justify-between">
                <h3 class="text-base font-bold text-[#1B2B20] dark:text-[#F2FAF5]">{{ $title }}</h3>
                <button type="button" @click="show = false" class="p-1 rounded-lg text-[#5E6B60] hover:text-[#1B2B20] dark:text-[#BFE3CC] dark:hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Drawer Body -->
            <div class="p-6 flex-1 overflow-y-auto">
                {{ $slot }}
            </div>

            @if (isset($footer))
                <!-- Drawer Footer -->
                <div class="px-6 py-4 bg-[#F2FAF5] dark:bg-[#0F2A1A]/50 border-t border-[#E3EBE5] dark:border-[#1F6B3E]">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
