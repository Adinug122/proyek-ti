<?php

    namespace App\Filament\Resources\Products\Tables;

    use Filament\Actions\BulkActionGroup;
    use Filament\Actions\DeleteBulkAction;
    use Filament\Actions\EditAction;
    use Filament\Tables\Columns\ImageColumn;
    use Filament\Tables\Columns\TextColumn;
    use Filament\Tables\Table;

    class ProductsTable
    {
        public static function configure(Table $table): Table
        {
            return $table
                ->columns([
                    TextColumn::make('name_product')
                        ->searchable(),
                    TextColumn::make('description')
                        ->searchable(),
                    TextColumn::make('price')
                        ->prefix('Rp ')
                        ->sortable(),
                    TextColumn::make('stock')
                        ->numeric()
                        ->sortable(),
                    ImageColumn::make('image')
                        ->disk('public')
                        ->visibility('public')
                        ->state(fn($record)=> asset('storage/'.$record->image))
                        ->imageHeight(60) 
                    ->square(),
                    TextColumn::make('category.name_category')
                        ->searchable()
                        ->sortable(),
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
                ])
                ->toolbarActions([
                    BulkActionGroup::make([
                        DeleteBulkAction::make(),
                    ]),
                ]);
        }
    }
