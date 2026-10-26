@props([
    'tabs' => [], // array of ['id' => '...', 'label' => '...', 'icon' => '...', 'badge' => '...']
    'defaultTab' => null,
])

@php
    $initialTab = $defaultTab ?? ($tabs[0]['id'] ?? '');
@endphp

<div x-data="{ currentTab: '{{ $initialTab }}' }" {{ $attributes->merge(['class' => 'w-full']) }}>
    <!-- Tab Headers -->
    <div class="border-b border-[#E3EBE5] dark:border-[#1F6B3E] flex space-x-2 overflow-x-auto scrollbar-none" role="tablist">
        @foreach ($tabs as $tab)
            <button
                type="button"
                role="tab"
                :aria-selected="currentTab === '{{ $tab['id'] }}'"
                @click="currentTab = '{{ $tab['id'] }}'; $dispatch('tab-changed', '{{ $tab['id'] }}')"
                :class="currentTab === '{{ $tab['id'] }}'
                    ? 'border-[#14432A] text-[#14432A] dark:border-[#3FAE6D] dark:text-[#3FAE6D] font-bold'
                    : 'border-transparent text-[#5E6B60] hover:text-[#1B2B20] hover:border-[#BFE3CC] dark:text-[#BFE3CC] dark:hover:text-[#F2FAF5] font-medium'"
                class="inline-flex items-center gap-2 py-3 px-4 border-b-2 text-sm whitespace-nowrap transition duration-150 focus:outline-none min-h-[44px]"
            >
                @if (isset($tab['icon']))
                    <span>{!! $tab['icon'] !!}</span>
                @endif
                <span>{{ $tab['label'] }}</span>

                @if (isset($tab['badge']))
                    <span
                        :class="currentTab === '{{ $tab['id'] }}'
                            ? 'bg-[#DFF2E6] text-[#14432A] dark:bg-[#1F6B3E]/60 dark:text-[#F2FAF5]'
                            : 'bg-[#F2FAF5] text-[#5E6B60] dark:bg-[#14432A] dark:text-[#BFE3CC]'"
                        class="px-2 py-0.5 text-xs font-bold rounded-full transition"
                    >
                        {{ $tab['badge'] }}
                    </span>
                @endif
            </button>
        @endforeach
    </div>

    <!-- Tab Panels Slot -->
    <div class="mt-4">
        {{ $slot }}
    </div>
</div>
