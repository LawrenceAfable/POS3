<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers'; 
    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'contact_number',
    ];

    protected $primaryKey = 'customer_id'; // Define the custom primary key

    public $incrementing = true; // Ensure it's an auto-incrementing key
    protected $keyType = 'int';  // Ensure the primary key is an integer

    // Specify relationships if necessary (e.g., orders)
    // public function orders()
    // {
    //     return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    // }
}
