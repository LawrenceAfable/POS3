<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_date',
        'total_amount',
        'discount',
        'tax',
        'user_id'
    ];

    protected $primaryKey = 'order_id'; // Define the custom primary key

    public $incrementing = true; // Ensure it's an auto-incrementing key
    protected $keyType = 'int';  // Ensure the primary key is an integer

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    // public function orderDetails()
    // {
    //     return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    // }

    // public function transaction()
    // {
    //     return $this->hasOne(Transaction::class, 'order_id', 'order_id');
    // }
}
