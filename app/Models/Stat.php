<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Stat extends Model
{
    use LogsActivity;

    protected $fillable = ['value', 'label', 'sort_order'];
}
