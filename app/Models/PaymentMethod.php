<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class PaymentMethod extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'account_number',
        'account_name',
        'logo_path',
        'is_active',
    ];

    /**
     * Relationship to product requests.
     */
    public function productRequests()
    {
        return $this->hasMany(ProductRequest::class);
    }
}
