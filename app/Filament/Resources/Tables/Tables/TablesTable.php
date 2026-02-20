<?php

namespace App\Filament\Resources\Tables\Tables;

use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->searchable(),
                    
         TextColumn::make('qr_code')
    ->label('QR')
    ->url(fn ($record) => $record->qr_code ? asset('storage/' . $record->qr_code) : null)
    ->formatStateUsing(fn ($state) => $state 
        ? new HtmlString('<img src="' . asset('storage/' . $state) . '" style="height:80px; width:80px; border-radius:8px; border:1px solid #ccc;">') 
        : '<span class="text-gray-400">Belum di-generate</span>'
    )
    ->html(),

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
                EditAction::make(), 
             Action::make('generate_qr')
    ->label('Generate QR')
    ->icon('heroicon-o-qr-code')
    ->color('success')
    ->requiresConfirmation()
    ->action(function ($record) {

  
        $tableNumber = $record->number;

        
     $url = route('order.start') . '?type=dine-in&table=' . $tableNumber;
     
        $fileName = 'qr/meja-' . $record->number . '.png';

        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new ImagickImageBackEnd()
        );

        $writer = new Writer($renderer);
        $qrCodeData = $writer->writeString($url);

        Storage::disk('public')->put($fileName, $qrCodeData);

        $record->update([
            'qr_code' => $fileName,
        ]);
    }),

                 Action::make('print_qr')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->url(fn ($record) => route('tables.print', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
