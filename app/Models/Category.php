<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;

    protected $table='category';

    protected $fillable = [
        'name',
        'description',
        'image',
        'is_featured',
        'sort_order',
        'is_active'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute()
    {
        return get_image_url($this->image, 'assets/images/default-cat.jpg');
    }


     protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('header_categories_tree');
            \Illuminate\Support\Facades\Cache::forget('home_categories_tree');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('header_categories_tree');
            \Illuminate\Support\Facades\Cache::forget('home_categories_tree');
        });
    }
}

