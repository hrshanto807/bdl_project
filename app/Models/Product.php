<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{

   protected $fillable = ['user_id', 'category_id', 'name', 'price', 'unit', 'img_url']; 
   

}