<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',          // The name of the category
        'description',   // The description of the category
    ];

    protected $primaryKey = 'category_id'; // Define the custom primary key

    public $incrementing = true; // Ensure it's an auto-incrementing key
    protected $keyType = 'int';  // Ensure the primary key is an integer

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
