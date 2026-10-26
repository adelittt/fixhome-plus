<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-white dark:bg-[#14432A]/50 border border-[#BFE3CC] dark:border-[#1F6B3E] rounded-xl font-bold text-xs text-[#14432A] dark:text-[#F2FAF5] uppercase tracking-wider shadow-sm hover:bg-[#F2FAF5] dark:hover:bg-[#14432A] focus:outline-none focus:ring-2 focus:ring-[#2E8B57] focus:ring-offset-2 disabled:opacity-25 transition min-h-[44px]']) }}>
    {{ $slot }}
</button>
