<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawalResoure extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
               'store_balance' => new StoreBallanceResource($this->storeBalance),
               'amount' => (float)(String) $this->amount,
               'bank_account_name' => $this->bank_account_name,
               'bank_account_number' => $this->bank_account_number,
               'bank_name' => $this->bank_name,
               'statu' => $this->status,

        ];
    }
}
