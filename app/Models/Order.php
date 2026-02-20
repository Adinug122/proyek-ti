<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
      protected $fillable = [
        'tables_id',
        'invoice_number',
        'order_type', 
        'status',
        'customer_name',
        'customer_phone',
        'total_price',
        'snap_token',
        'midtrans_order_id',
        'printed_at',
    ];

     public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

     public function table()
    {
        return $this->belongsTo(Table::class, 'tables_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    

}
