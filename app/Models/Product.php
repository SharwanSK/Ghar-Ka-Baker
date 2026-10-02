<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table='products';
    public $timestamps=false;
    protected $fillable=[
          'name', 
          'category',
          'unit',
          'price',
          'description',
          'image_url',
          'is_available', 
          'bakery_id',  
    ];
}
