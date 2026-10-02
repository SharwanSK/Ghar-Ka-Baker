<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'bakery_id',
        'name',
        'phone',
        'whatsapp_number',
        'city',
        'address',
    ];
}