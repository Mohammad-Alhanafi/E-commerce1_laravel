<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table = 'sliders'; 
    protected $fillable = ['title', 'image', 'link', 'status','order'];





public function isVideo()
{
    $extension = pathinfo($this->image, PATHINFO_EXTENSION);
    return in_array(strtolower($extension), ['mp4', 'mov', 'ogg', 'webm']);
}

protected static function booted(): void
{
    static::saved(function () {
        \Illuminate\Support\Facades\Cache::forget('home_sliders');
    });

    static::deleted(function () {
        \Illuminate\Support\Facades\Cache::forget('home_sliders');
    });
}
}