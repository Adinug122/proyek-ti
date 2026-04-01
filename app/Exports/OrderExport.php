<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery; // Pastikan ini yang dipakai
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class OrderExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function query()
    {
     
        return Order::query()
            ->with(['payment']) 
            ->where('status', 'paid')
            ->when($this->filters['created_from'], fn ($q) => $q->whereDate('created_at', '>=', $this->filters['created_from']))
            ->when($this->filters['created_until'], fn ($q) => $q->whereDate('created_at', '<=', $this->filters['created_until']));
    }

    public function headings(): array
    {
        return [
            'No. Invoice',
            'Nama Pelanggan',
            'Total Harga',
            'Metode Bayar',
            'Tanggal',
        ];
    }

    public function map($order): array
    {
        return [
            $order->payment?->invoice_number ?? '-', // Mengambil dari tabel payment
            $order->customer_name,
            $order->total_price,
            strtoupper($order->payment?->payment_type ?? 'Tunai'), //
            $order->created_at->format('d/m/Y H:i'),
        ];
    }
}