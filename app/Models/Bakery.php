<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Bakery extends Model{
    protected $table='bakeries';
    public $timestamps=false;
    protected $fillable=[
        'owner_name',
        'business_name',
        'whatsapp_number',
        'email',
        'password_hash',
        'business_phone',
        'business_city',
        'business_address',
        'jazzcash_number',
        'easypaisa_number',
        'bank_details',
        'default_currency',
        'default_order_status',
        'show_payment_reminders',
    ];
}