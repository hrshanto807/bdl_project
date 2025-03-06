<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import HasFactory
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory; // Add this line

    protected $fillable = ['user_id', 'category_id', 'name', 'price', 'unit', 'img_url'];  

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
