<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'bakery_id',
        'customer_id',
        'order_number',
        'order_type',
        'delivery_date',
        'delivery_time',
        'delivery_city',
        'delivery_address',
        'special_instructions',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}