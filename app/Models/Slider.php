<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'title',
        'description',
        'primary_btn_text',
        'primary_btn_url',
        'secondary_btn_text',
        'secondary_btn_url',
        'image_path',
        'overlay_opacity',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
