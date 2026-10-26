@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl border-[#E3EBE5] dark:border-[#1F6B3E] bg-white dark:bg-[#14432A]/50 text-xs p-3 text-[#1B2B20] dark:text-[#F2FAF5] focus:border-[#2E8B57] focus:ring-[#2E8B57]/20 shadow-sm min-h-[44px] transition']) }}>
