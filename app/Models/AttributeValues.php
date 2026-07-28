<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValues extends Model
{
    use HasFactory;

    protected $fillable = [
        'attributes_id',
        'value',
    ];
    public $timestamps = false;

    // العلاقة مع Attribute (مفرد)
    public function attribute()  // 👈 غير اسمها من attributes إلى attribute
    {
        return $this->belongsTo(Attributes::class, 'attributes_id');
    }

    // علاقة مع Variants
    public function variants()
    {
        return $this->belongsToMany(
            Variant::class,
            'variant_attribute_values',
            'attribute_values_id',
            'variants_id'
        );
    }
}
