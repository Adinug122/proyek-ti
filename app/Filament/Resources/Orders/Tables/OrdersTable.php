<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Exports\OrderExport;
use App\Models\Order;
use App\Models\Payment;
use Dom\Text;
use Filament\Actions\ActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('payment.invoice_number')
                ->label('No. Invoice')
                ->searchable() 
                ->copyable()  
                ->sortable(),
                TextColumn::make('order_type'),

                TextColumn::make('table.number')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->searchable(),

                TextColumn::make('customer_phone')
                    ->searchable(),
                TextColumn::make('payment.payment_type')
                        ->label('Metode Bayar')
                        ->badge() 
                        ->color(fn (string $state): string => match ($state) {
                            'qris' => 'info',
                            'tunai' => 'success',
                            default => 'gray',
                        })
                        ->formatStateUsing(fn (string $state) => strtoupper($state)) // Membuat teks jadi huruf besar
                        ->sortable(),
                TextColumn::make('total_price')
                    ->numeric() 
                    ->sortable(),

                TextColumn::make('status'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            

           ->headerActions([
                Action::make('export')
                    ->label('Unduh Laporan Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Mulai Tanggal')
                            ->default(now()->startOfMonth()),
                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                            ->default(now()),
                    ])
                    ->action(function (array $data) {
                        return Excel::download(
                            new OrderExport($data), 
                            'Laporan-Kasir-' . now()->format('d-m-Y') . '.xlsx'
                        );
                    })
            ])
           
            ->actions([
                
                ActionGroup::make([
                    // 1. DETAIL ORDER (STRUK)
                    Action::make('view_detail')
                        ->label('Detail Order')
                        ->icon('heroicon-o-document-text')
                        ->color('info')
                        ->modalHeading('Rincian Pesanan Pelanggan')
                        ->modalSubmitAction(false)
                        ->modalWidth('md')
                        ->modalContent(fn (Order $record) => view('order.show_order', [
                            'order' => $record->loadMissing('items'),
                        ])),

                
                    Action::make('Terima')
                        ->label('Terima Pembayaran')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn (Order $record) => $record->status === 'pending')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Pembayaran')
                        ->modalDescription('Pastikan Anda telah menerima uang/transfer dari pelanggan.')
                        ->action(function (Order $record) {
                            $record->update(['status' => 'paid']);
                            $payment = Payment::where('order_id', $record->id)->first();
                            
                            if ($payment) {
                                $payment->update([
                                    'transaction_status' => 'settlement',
                                    'updated_at' => now(),
                                ]);
                            }
                            Notification::make()
                                ->title('Status Berhasil Diperbarui')
                                ->body('Pesanan telah ditandai sebagai LUNAS.')
                                ->success()
                                ->send();
                        }),
                        Action::make('print')
    ->label('Print')
    ->icon('heroicon-o-printer')
    ->color('success')
    // Membuka tab baru untuk proses print
    ->url(fn (Order $record) => route('order.print', $record))
    ->openUrlInNewTab()
    // Tombol hanya muncul kalau statusnya sudah Paid
    ->visible(fn (Order $record) => $record->status === 'paid'),
                ])
                ->icon('heroicon-m-ellipsis-vertical') // Ini yang membuat jadi tombol titik tiga
                ->tooltip('Opsi')
                ->color('gray') 

            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}