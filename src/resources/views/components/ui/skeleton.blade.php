@props([
    'type' => 'card', // card, text, avatar, product, table
    'lines' => 3,
])

<div {{ $attributes->merge(['class' => 'animate-pulse']) }}>
    @if ($type === 'text')
        <div class="space-y-2.5">
            @for ($i = 0; $i < $lines; $i++)
                <div class="h-3.5 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-full {{ $i === $lines - 1 ? 'w-2/3' : 'w-full' }}"></div>
            @endfor
        </div>
    @elseif ($type === 'avatar')
        <div class="w-12 h-12 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-full"></div>
    @elseif ($type === 'product')
        <div class="bg-white dark:bg-[#14432A]/30 border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl overflow-hidden p-4 space-y-3">
            <div class="h-36 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-xl w-full"></div>
            <div class="h-4 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-full w-3/4"></div>
            <div class="h-3 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-full w-1/2"></div>
            <div class="flex justify-between items-center pt-2">
                <div class="h-5 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-full w-1/3"></div>
                <div class="h-8 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-xl w-20"></div>
            </div>
        </div>
    @elseif ($type === 'table')
        <div class="space-y-3">
            <div class="h-10 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-xl w-full"></div>
            @for ($i = 0; $i < 4; $i++)
                <div class="h-12 bg-[#E3EBE5]/60 dark:bg-[#1F6B3E]/20 rounded-xl w-full"></div>
            @endfor
        </div>
    @else
        <!-- Card Skeleton default -->
        <div class="bg-white dark:bg-[#14432A]/30 border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-full shrink-0"></div>
                <div class="space-y-1.5 flex-1">
                    <div class="h-4 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-full w-1/2"></div>
                    <div class="h-3 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-full w-1/3"></div>
                </div>
            </div>
            <div class="space-y-2">
                <div class="h-3 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-full w-full"></div>
                <div class="h-3 bg-[#E3EBE5] dark:bg-[#1F6B3E]/40 rounded-full w-4/5"></div>
            </div>
        </div>
    @endif
</div>
