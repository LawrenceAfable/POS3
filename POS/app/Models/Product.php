<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'category_id',
        'image_url',
        'quantity',
        'supplier_id',
        'sku',
        'low_stock_threshold',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $primaryKey = 'product_id'; // Define the custom primary key

    public $incrementing = true; // Ensure it's an auto-incrementing key
    protected $keyType = 'int';  // Ensure the primary key is an integer

    public function orderDetails()
    {
        return $this->hasMany(Order_Detail::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    // Define the relationship with Supplier (assuming a product belongs to a supplier)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
    // Audit Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
