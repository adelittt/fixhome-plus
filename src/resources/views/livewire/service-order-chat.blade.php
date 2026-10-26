<div wire:poll.3s class="bg-white rounded-3xl border border-slate-200/80 shadow-sm flex flex-col h-[520px] overflow-hidden">
    <!-- Header Chat -->
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 font-bold flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900">Diskusi Langsung (Live Chat)</h3>
                <p class="text-[11px] text-slate-500">Koordinasi pekerjaan dan kebutuhan material perbaikan</p>
            </div>
        </div>
        <div class="flex items-center gap-1.5 text-xs text-emerald-600 font-medium">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span>Terhubung</span>
        </div>
    </div>

    <!-- Messages Container -->
    <div id="chat-messages-container" class="flex-1 p-6 overflow-y-auto space-y-4 bg-slate-50/50">
        @if ($messages->isEmpty())
            <div class="h-full flex flex-col items-center justify-center text-center p-6 text-slate-400">
                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mb-2">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <p class="text-xs">Belum ada pesan. Mulai diskusikan detail pekerjaan dengan teknisi di sini.</p>
            </div>
        @else
            @foreach ($messages as $msg)
                @php
                    $isMe = $msg->sender_id === auth()->id();
                @endphp
                <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                    <div class="flex items-baseline gap-2 mb-1">
                        <span class="text-[11px] font-semibold text-slate-500">
                            {{ $isMe ? 'Anda' : $msg->sender->name }}
                        </span>
                        <span class="text-[10px] text-slate-400">
                            {{ $msg->created_at->format('H:i') }}
                        </span>
                    </div>
                    <div class="max-w-[75%] rounded-2xl px-4 py-2.5 text-sm leading-relaxed {{ $isMe ? 'bg-blue-600 text-white rounded-tr-none shadow-md shadow-blue-500/10' : 'bg-white text-slate-800 border border-slate-200/80 rounded-tl-none shadow-sm' }}">
                        {{ $msg->message }}
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Message Input -->
    <div class="p-4 bg-white border-t border-slate-100">
        <form wire:submit="sendMessage" class="flex items-center gap-2">
            <input 
                type="text" 
                wire:model="newMessage" 
                placeholder="Tulis pesan ke teknisi / pelanggan..." 
                class="flex-1 rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm px-4 py-3 placeholder-slate-400"
            />
            <button 
                type="submit" 
                class="px-5 py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm transition-all shadow-md shadow-blue-500/20 flex items-center justify-center shrink-0"
            >
                <span>Kirim</span>
                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
        @error('newMessage') <span class="text-xs text-rose-500 mt-1 block px-1">{{ $message }}</span> @enderror
    </div>
</div>

<script>
    function scrollChatToBottom() {
        const el = document.getElementById('chat-messages-container');
        if (el) el.scrollTop = el.scrollHeight;
    }
    document.addEventListener('livewire:initialized', () => {
        scrollChatToBottom();
        Livewire.on('message-sent', () => {
            setTimeout(scrollChatToBottom, 100);
        });
    });
</script>
