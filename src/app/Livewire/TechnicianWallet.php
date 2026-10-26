<?php

namespace App\Livewire;

use App\Models\ServiceCommission;
use App\Models\TechnicianWithdrawal;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TechnicianWallet extends Component
{
    use WithPagination;

    public string $amount = '';
    public string $bank_name = 'BCA';
    public string $account_number = '';
    public string $account_holder_name = '';
    public string $notes = '';
    public bool $showWithdrawModal = false;

    protected $rules = [
        'amount' => 'required|integer|min:20000',
        'bank_name' => 'required|string|max:50',
        'account_number' => 'required|string|min:5|max:50',
        'account_holder_name' => 'required|string|min:3|max:100',
        'notes' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'amount.required' => 'Nominal penarikan wajib diisi.',
        'amount.integer' => 'Nominal penarikan harus berupa angka.',
        'amount.min' => 'Batas minimal penarikan saldo adalah Rp20.000.',
        'bank_name.required' => 'Silakan pilih bank atau e-wallet tujuan.',
        'account_number.required' => 'Nomor rekening atau nomor e-wallet wajib diisi.',
        'account_holder_name.required' => 'Nama pemilik rekening wajib diisi.',
        'account_holder_name.min' => 'Nama pemilik rekening minimal 3 karakter.',
    ];

    public function mount(): void
    {
        if (! auth()->check() || ! auth()->user()->isTeknisi()) {
            abort(403, 'Hanya teknisi yang berhak mengakses Saldo dan Penarikan Dana ini.');
        }

        $this->account_holder_name = auth()->user()->name;
    }

    public function openWithdrawModal(): void
    {
        $this->resetValidation();
        $this->showWithdrawModal = true;
    }

    public function closeWithdrawModal(): void
    {
        $this->showWithdrawModal = false;
    }

    public function setQuickAmount(int $val): void
    {
        $available = $this->getAvailableBalance();
        $this->amount = (string) min($val, $available);
    }

    public function withdrawAll(): void
    {
        $available = $this->getAvailableBalance();
        $this->amount = (string) $available;
    }

    public function submitWithdrawal(): void
    {
        $this->validate();

        $available = $this->getAvailableBalance();
        $reqAmount = (int) $this->amount;

        if ($reqAmount > $available) {
            $this->addError('amount', 'Nominal penarikan melebihi saldo tersedia (Maksimal Rp' . number_format($available, 0, ',', '.') . ').');
            return;
        }

        TechnicianWithdrawal::create([
            'technician_id' => auth()->id(),
            'withdrawal_code' => TechnicianWithdrawal::generateCode(),
            'amount' => $reqAmount,
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'account_holder_name' => $this->account_holder_name,
            'notes' => $this->notes,
            'status' => 'menunggu',
        ]);

        $this->showWithdrawModal = false;
        $this->amount = '';
        $this->notes = '';

        session()->flash('success', 'Pengajuan penarikan dana berhasil dikirim! Admin akan segera memproses transfer ke rekening Anda.');
    }

    public function getAvailableBalance(): int
    {
        $techId = auth()->id();

        $totalEarned = (int) ServiceCommission::where('technician_id', $techId)
            ->whereIn('status', ['tercatat', 'dicairkan'])
            ->sum('technician_earning');

        $withdrawnOrPending = (int) TechnicianWithdrawal::where('technician_id', $techId)
            ->whereIn('status', ['menunggu', 'diproses', 'berhasil'])
            ->sum('amount');

        return max(0, $totalEarned - $withdrawnOrPending);
    }

    public function render()
    {
        $techId = auth()->id();

        $totalEarned = (int) ServiceCommission::where('technician_id', $techId)
            ->whereIn('status', ['tercatat', 'dicairkan'])
            ->sum('technician_earning');

        $totalSuccess = (int) TechnicianWithdrawal::where('technician_id', $techId)
            ->where('status', 'berhasil')
            ->sum('amount');

        $totalPending = (int) TechnicianWithdrawal::where('technician_id', $techId)
            ->whereIn('status', ['menunggu', 'diproses'])
            ->sum('amount');

        $availableBalance = max(0, $totalEarned - ($totalSuccess + $totalPending));

        $withdrawals = TechnicianWithdrawal::where('technician_id', $techId)
            ->latest()
            ->paginate(5, ['*'], 'withdrawalsPage');

        $commissions = ServiceCommission::where('technician_id', $techId)
            ->with('serviceOrder')
            ->latest()
            ->paginate(6, ['*'], 'commissionsPage');

        $bankOptions = [
            'BCA' => 'Bank Central Asia (BCA)',
            'Mandiri' => 'Bank Mandiri',
            'BRI' => 'Bank Rakyat Indonesia (BRI)',
            'BNI' => 'Bank Negara Indonesia (BNI)',
            'BSI' => 'Bank Syariah Indonesia (BSI)',
            'Jago' => 'Bank Jago',
            'SeaBank' => 'SeaBank Indonesia',
            'GoPay' => 'GoPay (Nomor HP)',
            'OVO' => 'OVO (Nomor HP)',
            'DANA' => 'DANA (Nomor HP)',
        ];

        return view('livewire.technician-wallet', [
            'availableBalance' => $availableBalance,
            'totalEarned' => $totalEarned,
            'totalSuccess' => $totalSuccess,
            'totalPending' => $totalPending,
            'withdrawals' => $withdrawals,
            'commissions' => $commissions,
            'bankOptions' => $bankOptions,
        ]);
    }
}
