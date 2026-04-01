<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_type')
                    ->options(['dine_in' => 'Dine in', 'take_away' => 'Take away'])
                    ->required(),
                TextInput::make('tables_id')
                    ->numeric(),
                TextInput::make('customer_name')
                    ->required(),
                TextInput::make('customer_phone')
                    ->tel(),
                TextInput::make('total_price')
                    ->required()
                    ->numeric(),
                Select::make('status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid', 'cancelled' => 'Cancelled', 'expired' => 'Expired'])
                    ->default('pending')
                    ->required(),
                TextInput::make('midtrans_order_id'),
                DateTimePicker::make('printed_at'),
            ]);
    }
}
