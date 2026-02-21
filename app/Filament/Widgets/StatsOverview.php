<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {

$totalProdukTerjual = OrderItem::whereHas('order', function ($query) {
    $query->where('status', 'paid')->whereDate('created_at', now()->today());
})->sum('qty');

    $totalPesananHariIni = Order::whereIn('status',['paid','pending'])->whereDate('created_at',now()->today())
                    ->count();

        return [
Stat::make('Total Pendapatan', 'Rp' . number_format(Order::where('status', 'paid')
->whereDate('created_at',now()->today())
->sum('total_price'), 0, ',', '.'))
    ->description('Total uang masuk dari pesanan sukses')
    ->color('success')
    ->icon('heroicon-o-banknotes'),

            Stat::make('Total Produk Terjual', $totalProdukTerjual)
            ->description('Total Produk Yang Terjual hari ini')
            ->icon('heroicon-o-shopping-cart')
            ->color('info')
            ,
            Stat::make('Total Pesanan Masuk', $totalPesananHariIni)
            ->description('Total Pesanan Masuk Hari ini')
            ->color('primary')
            ->icon('heroicon-o-clipboard-document-list'),
        ];


    }
}
