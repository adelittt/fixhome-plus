@props([
    'headers' => [],
    'empty' => false,
    'emptyMessage' => 'Belum ada data pada tabel ini.',
])

<div class="w-full bg-white dark:bg-[#14432A]/30 border border-[#E3EBE5] dark:border-[#1F6B3E] rounded-2xl shadow-card overflow-hidden">
    <div class="overflow-x-auto scrollbar-none">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#F2FAF5] dark:bg-[#0F2A1A]/60 border-b border-[#E3EBE5] dark:border-[#1F6B3E]">
                    @foreach ($headers as $header)
                        <th scope="col" class="px-5 py-3.5 text-xs font-bold text-[#14432A] dark:text-[#3FAE6D] uppercase tracking-wider whitespace-nowrap">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-[#E3EBE5] dark:divide-[#1F6B3E] text-sm text-[#1B2B20] dark:text-[#F2FAF5]">
                @if ($empty)
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-6 py-12 text-center text-[#5E6B60] dark:text-[#BFE3CC]">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-10 h-10 text-[#5E6B60]/40 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span>{{ $emptyMessage }}</span>
                            </div>
                        </td>
                    </tr>
                @else
                    {{ $slot }}
                @endif
            </tbody>
        </table>
    </div>

    @if (isset($pagination))
        <div class="px-5 py-3 border-t border-[#E3EBE5] dark:border-[#1F6B3E] bg-[#F2FAF5]/50 dark:bg-[#0F2A1A]/30">
            {{ $pagination }}
        </div>
    @endif
</div>
