<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'badge', 'description', 'price', 'is_from_price', 'image_url', 'category_id'];

    /**
     * Get the category that the product belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
