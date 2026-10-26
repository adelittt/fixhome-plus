<?php

namespace App\Livewire;

use App\Models\ChatMessage;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class ServiceOrderChat extends Component
{
    public ServiceOrder $order;
    public string $newMessage = '';

    protected function rules(): array
    {
        return [
            'newMessage' => ['required', 'string', 'max:1000'],
        ];
    }

    public function mount(ServiceOrder $order): void
    {
        $this->order = $order;
        $this->authorizeAccess();
        $this->markMessagesAsRead();
    }

    public function authorizeAccess(): void
    {
        $userId = auth()->id();

        if (
            $userId !== $this->order->customer_id &&
            $userId !== $this->order->technician_id &&
            ! auth()->user()->isAdmin()
        ) {
            abort(403, 'Anda tidak memiliki hak akses percakapan pada pesanan ini.');
        }
    }

    public function markMessagesAsRead(): void
    {
        ChatMessage::query()
            ->where('service_order_id', $this->order->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function sendMessage(): void
    {
        $this->authorizeAccess();
        $this->validate();

        $senderId = auth()->id();
        $receiverId = ($senderId === $this->order->customer_id)
            ? $this->order->technician_id
            : $this->order->customer_id;

        ChatMessage::create([
            'service_order_id' => $this->order->id,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => trim($this->newMessage),
            'read_at' => null,
        ]);

        $this->newMessage = '';
        $this->dispatch('message-sent');
    }

    public function render()
    {
        $this->markMessagesAsRead();

        $messages = ChatMessage::query()
            ->with(['sender'])
            ->where('service_order_id', $this->order->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('livewire.service-order-chat', [
            'messages' => $messages,
        ]);
    }
}
