<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Product extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'badge', 'description', 'price', 'is_from_price', 'in_stock', 'image_url', 'category_id'];

    /**
     * Get the category that the product belongs to.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
