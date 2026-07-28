<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class  Attributes extends Model{
protected $fillable = ['name'];
public $timestamps = false;
public function values(){
    return $this->hasMany(AttributeValues::class,'attributes_id');
}


}
