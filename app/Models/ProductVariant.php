<?php

namespace App\Models;

// تم حذف use Attribute; لأنها تسبب تعارضاً برمجياً
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttributeValues;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'attribute_value_id',
        'stock',
        'price_override',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function attributeValue() // يفضل الاسم المفرد هنا
    {
        return $this->belongsTo(AttributeValues::class, 'attribute_value_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_variant_id');
    }
}