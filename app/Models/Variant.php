<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    
    protected $table = 'variants'; 

    protected $fillable = [
        'product_id',      
        'name',         
        'additional_price',
        'variant_price',
        'stock', 
        'sku',
        'status',    
        'notes',   
        'variant_image', 
        'color'

    ];

    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute()
    {
        return get_image_url($this->variant_image, null);
    }


   public function attributeValues()
{
    return $this->belongsToMany(
        AttributeValues::class,
        'variant_attribute_values',
        'variants_id',         
        'attribute_values_id'   
    );
}

}
