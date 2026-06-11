<?php

namespace App\Repositories;

use App\interfaces\TransactionRepositoryInterface;
use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $exceute)
    {
        $query = Transaction::where(function ($query) use ($search) {
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

    public function getById(
        string $id
    ) {
        $query = Transaction::where('id', $id);

        return $query->first();
    }

    public function getByCode(
        string $code
    ) {
        $query = Transaction::where('code', $code);

        return $query->first();
    }
}
