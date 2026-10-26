<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2.5 bg-[#14432A] hover:bg-[#1F6B3E] active:bg-[#0F2A1A] border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-[#2E8B57] focus:ring-offset-2 transition shadow-md shadow-[#14432A]/20 min-h-[44px]']) }}>
    {{ $slot }}
</button>
