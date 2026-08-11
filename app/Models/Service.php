<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Service extends Model
{
    use LogsActivity;

    protected $fillable = ['title', 'icon', 'description', 'sort_order'];
}
