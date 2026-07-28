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


     protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];
}

