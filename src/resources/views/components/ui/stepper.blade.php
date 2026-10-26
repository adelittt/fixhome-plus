@props([
    'steps' => [], // array of ['title' => '...', 'desc' => '...']
    'currentStep' => 1,
])

<div {{ $attributes->merge(['class' => 'w-full py-4']) }}>
    <div class="flex items-center justify-between relative">
        @foreach ($steps as $index => $step)
            @php
                $stepNumber = $index + 1;
                $isCompleted = $stepNumber < $currentStep;
                $isCurrent = $stepNumber === (int) $currentStep;
                $isUpcoming = $stepNumber > $currentStep;
            @endphp

            <div class="flex flex-col items-center flex-1 relative z-10">
                <!-- Step Circle -->
                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-200 {{
                    $isCompleted
                        ? 'bg-[#14432A] text-white shadow-sm shadow-[#14432A]/20'
                        : ($isCurrent
                            ? 'bg-[#2E8B57] text-white ring-4 ring-[#DFF2E6] dark:ring-[#1F6B3E]/50'
                            : 'bg-white dark:bg-[#14432A] border-2 border-[#E3EBE5] dark:border-[#1F6B3E] text-[#5E6B60] dark:text-[#BFE3CC]')
                }}">
                    @if ($isCompleted)
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    @else
                        <span>{{ $stepNumber }}</span>
                    @endif
                </div>

                <!-- Label -->
                <div class="mt-2 text-center px-1">
                    <p class="text-xs md:text-sm font-bold {{ $isCurrent ? 'text-[#14432A] dark:text-[#3FAE6D]' : ($isCompleted ? 'text-[#1B2B20] dark:text-[#F2FAF5]' : 'text-[#5E6B60] dark:text-[#BFE3CC]') }}">
                        {{ $step['title'] ?? $step }}
                    </p>
                    @if (isset($step['desc']))
                        <p class="hidden sm:block text-[11px] text-[#5E6B60] dark:text-[#BFE3CC]/80 mt-0.5">{{ $step['desc'] }}</p>
                    @endif
                </div>
            </div>

            @if (!$loop->last)
                <!-- Connector Line -->
                <div class="flex-1 h-0.5 absolute top-5 -translate-y-1/2 z-0" style="left: calc({{ ($index / (count($steps) - 1)) * 100 }}% + 24px); width: calc({{ 100 / (count($steps) - 1) }}% - 48px);">
                    <div class="h-full transition-all duration-300 {{ $stepNumber < $currentStep ? 'bg-[#14432A]' : 'bg-[#E3EBE5] dark:bg-[#1F6B3E]' }}"></div>
                </div>
            @endif
        @endforeach
    </div>
</div>
