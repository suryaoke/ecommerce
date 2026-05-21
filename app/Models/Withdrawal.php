<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use UUID;

    protected $fillable = [
        'store_ballance_id',
        'store_ballance_id',
        'amount',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];
    public function scopeSearch($query, $search)
    {
        return $query->where('storeBalance.store', function ($q) use ($search) {
            $q->where('name', 'LIKE', '%' . $search . '%');
        });
    }

    public function storeBalance()
    {
        return $this->belongsTo(StoreBallance::class);
    }
}
