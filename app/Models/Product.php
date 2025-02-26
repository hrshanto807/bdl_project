<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{

   protected $fillable = ['user_id', 'category_id', 'name', 'price', 'unit', 'img_url'];

   protected static function booted()
   {
       static::addGlobalScope('active', function (Builder $builder) {
           $builder->where('active', 1);
       });
   }
   

}