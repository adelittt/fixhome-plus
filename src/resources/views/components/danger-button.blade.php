<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-[#B3402F] hover:bg-[#B3402F]/90 active:bg-[#8F2E20] border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-[#B3402F] focus:ring-offset-2 transition shadow-md shadow-[#B3402F]/20 min-h-[44px]']) }}>
    {{ $slot }}
</button>
