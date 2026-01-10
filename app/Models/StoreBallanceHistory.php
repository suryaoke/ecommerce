<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class StoreBallanceHistory extends Model
{
    use UUID;

    protected $fillable = [

        'store_ballance_id',
        'type',
        'reference_id',
        'reference_type',
        'amount',
        'remarks',

    ];

    public function storeBalance()
    {
        return $this->belongsTo(StoreBallance::class);
    }
}
