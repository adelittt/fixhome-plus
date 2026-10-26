<?php

namespace App\Filament\Admin\Pages;

use App\Models\MarketplaceCommission;
use App\Models\ServiceCommission;
use Filament\Pages\Page;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CommissionReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Laporan Komisi Terpisah';

    protected static ?string $title = 'Laporan Komisi Platform (Terpisah & Transparan)';

    protected static ?string $navigationGroup = 'Laporan & Keuangan';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.admin.pages.commission-report';

    public ?string $startDate = null;

    public ?string $endDate = null;

    public string $statusFilter = 'semua';

    public function mount(): void
    {
        // Default menampilkan seluruh catatan atau bulan berjalan
    }

    public function resetFilters(): void
    {
        $this->startDate = null;
        $this->endDate = null;
        $this->statusFilter = 'semua';
    }

    /**
     * Unduh Laporan Komisi Jasa (5%) dalam format CSV
     */
    public function exportServiceCommissionsCsv(): StreamedResponse
    {
        $query = ServiceCommission::with(['serviceOrder', 'technician'])->latest();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->statusFilter !== 'semua') {
            $query->where('status', $this->statusFilter);
        }

        $records = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-komisi-jasa-'.date('Ymd-His').'.csv"',
        ];

        return response()->stream(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID',
                'No. Pesanan Jasa',
                'Nama Teknisi',
                'Nilai Dasar Jasa (Rp)',
                'Tarif Platform',
                'Komisi Platform 5% (Rp)',
                'Hak Teknisi 95% (Rp)',
                'Status',
                'Waktu Selesai',
            ]);

            foreach ($records as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->serviceOrder?->order_number ?? '-',
                    $row->technician?->name ?? '-',
                    $row->gross_amount,
                    '5%',
                    $row->platform_commission,
                    $row->technician_earning,
                    $row->status,
                    $row->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Unduh Laporan Komisi Marketplace (2%) dalam format CSV
     * Ongkir dicatat sebagai kolom informasi terpisah dan tidak masuk ke dasar komisi (AB-004)
     */
    public function exportMarketplaceCommissionsCsv(): StreamedResponse
    {
        $query = MarketplaceCommission::with(['productOrder.store', 'store'])->latest();

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->statusFilter !== 'semua') {
            $query->where('status', $this->statusFilter);
        }

        $records = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-komisi-marketplace-'.date('Ymd-His').'.csv"',
        ];

        return response()->stream(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'ID',
                'No. Pesanan Produk Toko',
                'Nama Mitra Toko',
                'Nilai Produk Murni / Dasar Komisi (Rp)',
                'Ongkos Kirim Toko Terpisah (Rp)',
                'Tarif Platform',
                'Komisi Platform 2% (Rp)',
                'Hak Mitra Toko 98% (Rp)',
                'Status',
                'Waktu Selesai',
            ]);

            foreach ($records as $row) {
                fputcsv($handle, [
                    $row->id,
                    $row->productOrder?->order_number ?? '-',
                    $row->store?->name ?? '-',
                    $row->gross_product_amount,
                    $row->productOrder?->shipping_fee ?? 0,
                    '2%',
                    $row->platform_commission,
                    $row->store_earning,
                    $row->status,
                    $row->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    protected function getViewData(): array
    {
        // Kueri Service Commissions (Eager Loading PK-14)
        $serviceQuery = ServiceCommission::with(['serviceOrder', 'technician'])->latest();
        if ($this->startDate) {
            $serviceQuery->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $serviceQuery->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->statusFilter !== 'semua') {
            $serviceQuery->where('status', $this->statusFilter);
        }
        $serviceCommissions = $serviceQuery->get();

        // Kueri Marketplace Commissions (Eager Loading PK-14)
        $marketplaceQuery = MarketplaceCommission::with(['productOrder', 'store'])->latest();
        if ($this->startDate) {
            $marketplaceQuery->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $marketplaceQuery->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->statusFilter !== 'semua') {
            $marketplaceQuery->where('status', $this->statusFilter);
        }
        $marketplaceCommissions = $marketplaceQuery->get();

        // Hitung KPI secara terpisah (AB-005)
        $totalServiceGross = $serviceCommissions->sum('gross_amount');
        $totalServiceCommission = $serviceCommissions->sum('platform_commission');
        $totalTechnicianEarning = $serviceCommissions->sum('technician_earning');

        $totalMarketplaceGross = $marketplaceCommissions->sum('gross_product_amount');
        $totalMarketplaceCommission = $marketplaceCommissions->sum('platform_commission');
        $totalStoreEarning = $marketplaceCommissions->sum('store_earning');

        return [
            'serviceCommissions' => $serviceCommissions,
            'marketplaceCommissions' => $marketplaceCommissions,
            'stats' => [
                'totalServiceGross' => $totalServiceGross,
                'totalServiceCommission' => $totalServiceCommission,
                'totalTechnicianEarning' => $totalTechnicianEarning,
                'countService' => $serviceCommissions->count(),
                'totalMarketplaceGross' => $totalMarketplaceGross,
                'totalMarketplaceCommission' => $totalMarketplaceCommission,
                'totalStoreEarning' => $totalStoreEarning,
                'countMarketplace' => $marketplaceCommissions->count(),
            ],
        ];
    }
}
