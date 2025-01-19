<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order_Detail extends Model
{
    protected $table = 'order_details';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
        'discount',
        'tax'
    ];

    protected $primaryKey = 'order_detail_id'; // Define the custom primary key

    public $incrementing = true; // Ensure it's an auto-incrementing key
    protected $keyType = 'int';  // Ensure the primary key is an integer

    // public function order()
    // {
    //     return $this->belongsTo(Order::class, 'order_id', 'order_id');
    // }

    // public function product()
    // {
    //     return $this->belongsTo(Product::class, 'product_id', 'product_id');
    // }
}
