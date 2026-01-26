<?php

namespace App\Repositories;

use App\interfaces\StoreRepositoryInterface;
use App\Models\Store;

class StoreRepository implements StoreRepositoryInterface
{
    public function getAll(?string $search,?bool $isVerified, ?int $limit,bool $exceute)
    {
        $query = Store::where(function ($query) use ($search, $isVerified) {
            if ($search) {
                $query->search($search);
            }
            if($isVerified !== null) {
                $query->where('is_verified', $isVerified);
            }
        });

        if ($limit) {
            $query->take($limit);
        }

        if ($exceute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search,?bool $isVerified, ?int $rowPerPage)
    {
        $query = $this->getAll(
            $search,
            $rowPerPage,
            $isVerified,
            false
        );

        return $query->paginate($rowPerPage);
    }
}
