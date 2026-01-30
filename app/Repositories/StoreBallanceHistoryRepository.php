<?php

namespace App\Repositories;

use App\interfaces\StoreBallanceHistoryRepositoryInterface;
use App\Models\StoreBallanceHistory;

class StoreBallanceHistoryRepository implements StoreBallanceHistoryRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $exceute)
    {
        $query = StoreBallanceHistory::where(function ($query) use ($search) {
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
