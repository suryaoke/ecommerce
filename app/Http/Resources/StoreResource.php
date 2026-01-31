<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->when($this->relationLoaded('user'), function () {
                return new UserResource($this->user);
            }),
            'name' => $this->name,
            'logo' => $this->logo ? config('app.url') . '/storage/' . $this->logo : null, // ← Lebih efisien
            'about' => $this->about,
            'phone' => $this->phone,
            'address_id' => $this->address_id,
            'city' => $this->city,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'is_verified' => (bool) $this->is_verified
        ];
    }
}
