<?php

namespace App\Repositories;

use App\interfaces\WithdrawalRepositoryInterface;
use App\Models\Withdrawal;
use Exception;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Psr7\UploadedFile;

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

    public function getById(
        string $id
    ) {
        $query = Withdrawal::where('id', $id);

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $withdrawal = new Withdrawal();
            $withdrawal->store_balance_id = $data['store_balance_id'];
            $withdrawal->amount = $data['amount'];
            $withdrawal->bank_account_name = $data['bank_account_name'];
            $withdrawal->bank_account_number = $data['bank_account_number'];
            $withdrawal->bank_name = $data['bank_name'];
            $withdrawal->save();

            $storeBalanceRepository = new StoreBallanceRepository;
            $storeBalanceRepository->debit($data['store_balance_id'], $data['amount']);

            $storeBalanceRepositoryRepository = new StoreBallanceHistoryRepository;
            $storeBalanceRepository->create([
                'store_balance_id' => $withdrawal->store_balance_id,
                'type' =>  'withdraw',
                'reference_id' => $withdrawal->id,
                'reference_type' => Withdrawal::class,
                'amount' => $data['amount'],
                'remarks' => "Permintaan penerikan dana ke {$withdrawal->bank_name}  = {$withdrawal->bank_account_number}",
            ]);
            DB::commit();

            return $withdrawal;
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function approve(
        string $id,
        UploadedFile $proof
    ) {
        DB::beginTransaction();

        try {
            $withdrawal = Withdrawal::find($id);

            $withdrawal->status = 'approved';
            $withdrawal->proof = $proof->store('assets/withdrawal', 'public');
            $withdrawal->save();

            $storeBalanceHistoryRepository = new StoreBallanceHistoryRepository;

            $storeBalanceHistoryRepository->create([
                'store_balance_id' => $withdrawal->store_balance_id,
                'type' => 'withdraw',
                'reference_id' => $withdrawal->id,
                'reference_type' => Withdrawal::class,
                'amount' => -$withdrawal->amount,
                'remarks' => "Permintaan penarikan dana ke {$withdrawal->bank_name} - {$withdrawal->bank_account_number} disetujui"
            ]);

            DB::commit();

            return $withdrawal;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
