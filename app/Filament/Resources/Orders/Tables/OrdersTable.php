<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use Dom\Text;
use Filament\Actions\ActionGroup;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('order_type'),

                TextColumn::make('table.number')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->searchable(),

                TextColumn::make('customer_phone')
                    ->searchable(),

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

            ->filters([
                //
            ])

            ->recordActions([
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
                            
                            Notification::make()
                                ->title('Status Berhasil Diperbarui')
                                ->body('Pesanan telah ditandai sebagai LUNAS.')
                                ->success()
                                ->send();
                        }),
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