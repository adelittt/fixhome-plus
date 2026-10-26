<div
    x-data="{
        toasts: [],
        add(toast) {
            const id = Date.now();
            this.toasts.push({
                id,
                message: toast.message || toast,
                type: toast.type || 'success',
                title: toast.title || (toast.type === 'danger' ? 'Gagal' : 'Berhasil')
            });
            setTimeout(() => {
                this.remove(id);
            }, 4000);
        },
        remove(id) {
            this.toasts = this.toasts.filter(t => t.id !== id);
        }
    }"
    x-on:toast.window="add($event.detail)"
    x-on:notify.window="add($event.detail)"
    class="fixed top-5 right-5 z-50 flex flex-col gap-2.5 max-w-sm w-full pointer-events-none px-4 sm:px-0"
    role="region"
    aria-live="polite"
>
    <template x-for="t in toasts" :key="t.id">
        <div
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="pointer-events-auto p-4 rounded-2xl shadow-elevated border flex items-start gap-3 backdrop-blur-md"
            :class="{
                'bg-white dark:bg-[#14432A] border-[#BFE3CC] text-[#14432A] dark:text-[#F2FAF5]': t.type === 'success',
                'bg-white dark:bg-[#14432A] border-[#F5B8B0] text-[#B3402F] dark:text-[#F5B8B0]': t.type === 'danger' || t.type === 'error',
                'bg-white dark:bg-[#14432A] border-[#F5DE9B] text-[#9A6F14] dark:text-[#E0A526]': t.type === 'warning',
                'bg-white dark:bg-[#14432A] border-[#BCD0E2] text-[#4A6B8A] dark:text-[#BCD0E2]': t.type === 'info',
            }"
        >
            <!-- Icon -->
            <div class="shrink-0 mt-0.5">
                <template x-if="t.type === 'success'">
                    <div class="w-6 h-6 rounded-full bg-[#DFF2E6] flex items-center justify-center text-[#1F6B3E]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </template>
                <template x-if="t.type === 'danger' || t.type === 'error'">
                    <div class="w-6 h-6 rounded-full bg-[#FDF2F0] flex items-center justify-center text-[#B3402F]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                </template>
                <template x-if="t.type === 'warning'">
                    <div class="w-6 h-6 rounded-full bg-[#FEF8E7] flex items-center justify-center text-[#E0A526]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </template>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold leading-none mb-1" x-text="t.title"></p>
                <p class="text-xs text-[#5E6B60] dark:text-[#BFE3CC]" x-text="t.message"></p>
            </div>

            <!-- Close button -->
            <button type="button" @click="remove(t.id)" class="text-[#5E6B60] hover:text-[#1B2B20] dark:text-[#BFE3CC] transition p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </template>
</div>
