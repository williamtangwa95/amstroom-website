<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    /**
     * Get the products associated with the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Auto-generate slug when name is set.
     */
    protected static function boot()
    {
        parent::boot();
        static::saving(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
}
