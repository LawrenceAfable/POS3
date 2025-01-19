<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'user_id',         // Added user_id for the foreign key reference to users table
        'order_date',
        'total_amount',
        'discount',
        'tax',
    ];

    protected $primaryKey = 'order_id'; // Define the custom primary key

    public $incrementing = true; // Ensure it's an auto-incrementing key
    protected $keyType = 'int';  // Ensure the primary key is an integer

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function user() // Relationship to the User model (admin/cashier)
    {
        return $this->belongsTo(User::class, 'user_id', 'id'); // Refers to the `user_id` column
    }

    public function orderDetails()
    {
        return $this->hasMany(Order_Detail::class, 'order_id', 'order_id');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'order_id');
    }
}
