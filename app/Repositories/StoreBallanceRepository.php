<?php

namespace App\Repositories;

use App\interfaces\StoreBallanceRepositoryInterface;
use App\Models\StoreBallance;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreBallanceRepository implements StoreBallanceRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $exceute)
    {
        $query = StoreBallance::where(function ($query) use ($search) {
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
        $query = StoreBallance::where('id', $id);

        return $query->first();
    }

    public function credit(string $id, string $amount)
    {
        DB::beginTransaction();

        try {
            $storeBallance = StoreBallance::find($id);

            $storeBallance->balance = bcadd($storeBallance->balance, $amount, 2);

            $storeBallance->save();

            DB::commit();

            return $storeBallance;
        } catch (\Exception  $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function debit(string $id, string $amount)
    {
        DB::beginTransaction();

        try {
            $storeBallance = StoreBallance::find($id);

            if (bccomp($storeBallance->balance, $amount, 2) < 0) {
                throw new Exception("Saldo tidak mencukupi");
            }

            $storeBallance->balance = bcsub($storeBallance->balance, $amount, 2);

            $storeBallance->save();

            DB::commit();

            return $storeBallance;
        } catch (\Exception  $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }
}
