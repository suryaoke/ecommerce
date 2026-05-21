<?php

namespace App\Repositories;

use App\interfaces\WithdrawalRepositoryInterface;
use App\Models\Withdrawal;

class WithdrawalRepository implements WithdrawalRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $exceute)
    {
        $query = Withdrawal::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
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

    public function getAllPaginated(?string $search, ?int $rowPerPage)
    {
        $query = $this->getAll(
            $search,
            $rowPerPage,
            false
        );

        return $query->paginate($rowPerPage);
    }
}
