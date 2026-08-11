<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Category;
use App\Models\Order;
use App\Models\ProductVariant;
class Product extends Model
{
    use HasFactory;

    // The name of table in database
    protected $table = 'products_tabel';

    protected $fillable = [
        'name',
        'description',
        'care_instructions',
        'price',
        'stock',
        'status',
        'image',
        'category_id',
        'sku',
        'is_featured',
        'is_active',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')->withPivot('quantity');
    }


    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class , 'product_id');
    }
    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'related_products', 'product_id', 'related_id');
    }

    public function getImageUrlAttribute()
    {
        return get_image_url($this->image, 'assets/images/default-product.svg');
    }

 



    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('header_categories_tree');
            \Illuminate\Support\Facades\Cache::forget('home_categories_tree');
            \Illuminate\Support\Facades\Cache::forget('home_featured_products');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('header_categories_tree');
            \Illuminate\Support\Facades\Cache::forget('home_categories_tree');
            \Illuminate\Support\Facades\Cache::forget('home_featured_products');
        });
    }
}
