<?php

namespace Tests\Feature;

use App\Enums\ServiceOrderStatus;
use App\Enums\UserRole;
use App\Livewire\ServiceOrderChat;
use App\Models\ChatMessage;
use App\Models\ServiceCategory;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LiveChatTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;
    protected User $technician;
    protected User $unauthorizedUser;
    protected ServiceCategory $category;
    protected ServiceOrder $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create(['role' => UserRole::PELANGGAN]);
        $this->technician = User::factory()->create(['role' => UserRole::TEKNISI]);
        $this->unauthorizedUser = User::factory()->create(['role' => UserRole::PELANGGAN]);

        $this->category = ServiceCategory::create([
            'name' => 'Perawatan AC',
            'slug' => 'perawatan-ac',
        ]);

        $this->order = ServiceOrder::create([
            'order_number' => 'SRV-TEST-CHAT',
            'customer_id' => $this->customer->id,
            'technician_id' => $this->technician->id,
            'service_category_id' => $this->category->id,
            'job_description' => 'Cuci AC split 1 PK ruang tamu bocor air',
            'scheduled_at' => now()->addDay(),
            'address' => 'Jl. RS Fatmawati No. 88, Jakarta Selatan',
            'latitude' => -6.2615,
            'longitude' => 106.8106,
            'distance_km' => 3.0,
            'base_service_fee' => 90000,
            'distance_fee' => 0,
            'total_service_fee' => 90000,
            'status' => ServiceOrderStatus::DITERIMA,
        ]);
    }

    /**
     * Pelanggan dapat mengirim pesan ke teknisi pada pesanan terkait.
     */
    public function test_customer_can_send_chat_message(): void
    {
        Livewire::actingAs($this->customer)
            ->test(ServiceOrderChat::class, ['order' => $this->order])
            ->set('newMessage', 'Halo Mas Teknisi, apakah perlu disiapkan tangga lipat?')
            ->call('sendMessage')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('chat_messages', [
            'service_order_id' => $this->order->id,
            'sender_id' => $this->customer->id,
            'receiver_id' => $this->technician->id,
            'message' => 'Halo Mas Teknisi, apakah perlu disiapkan tangga lipat?',
        ]);
    }

    /**
     * Teknisi dapat membalas pesan chat dari pelanggan.
     */
    public function test_technician_can_reply_to_chat_message(): void
    {
        // Pesan awal dari pelanggan
        ChatMessage::create([
            'service_order_id' => $this->order->id,
            'sender_id' => $this->customer->id,
            'receiver_id' => $this->technician->id,
            'message' => 'AC saya bocor air di bagian indoor',
        ]);

        Livewire::actingAs($this->technician)
            ->test(ServiceOrderChat::class, ['order' => $this->order])
            ->assertSee('AC saya bocor air di bagian indoor')
            ->set('newMessage', 'Baik Bu, kemungkinan talang drainase tersumbat lumut. Saya bawa peralatan lengkap.')
            ->call('sendMessage')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('chat_messages', [
            'service_order_id' => $this->order->id,
            'sender_id' => $this->technician->id,
            'receiver_id' => $this->customer->id,
            'message' => 'Baik Bu, kemungkinan talang drainase tersumbat lumut. Saya bawa peralatan lengkap.',
        ]);
    }

    /**
     * Otorisasi Hak Akses Chat (FR-007, Matriks Hak Akses PRD 8):
     * Pengguna pihak ketiga (bukan pelanggan atau teknisi pemilik pesanan)
     * DITOLAK saat mencoba membuka percakapan (HTTP 403 Forbidden).
     */
    public function test_third_party_user_cannot_access_or_send_messages_in_chat(): void
    {
        Livewire::actingAs($this->unauthorizedUser)
            ->test(ServiceOrderChat::class, ['order' => $this->order])
            ->assertForbidden();
    }
}
