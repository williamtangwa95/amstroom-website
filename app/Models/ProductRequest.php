<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ProductRequest extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'request_type',
        'details',
        'total_price',
        'status'
    ];

    /**
     * Decode JSON details if request type is 'cart'.
     *
     * @return array|null
     */
    public function getCartItemsAttribute()
    {
        if ($this->request_type === 'cart') {
            return json_decode($this->details, true);
        }
        return null;
    }
}
