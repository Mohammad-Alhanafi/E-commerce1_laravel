<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'total_price',
        'status',
        'payment_method',
        'shipping_method', 
        'notes',
        'customer_name',  
        'customer_phone',
        'city',           
        'address',
    ];

    // علاقة المستخدم (لجلب الرقم والاسم الأساسي)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // علاقة المنتجات
   public function products()
{
    return $this->belongsToMany(Product::class, 'order_items')
                ->withPivot('quantity', 'price', 'variant_id') 
                ->withTimestamps();
}
}