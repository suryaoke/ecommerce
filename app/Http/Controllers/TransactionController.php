<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\TransactionResource;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class TransactionController extends Controller implements HasMiddleware
{
    private TransactionRepository $transactionRepository;
    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(
                PermissionMiddleware::using(['transaction-list|transaction-create|transaction-edit|transaction-delete']),
                only: ['index', 'getAllPaginated', 'show', 'updateVerifiedStatus']
            ),

            new Middleware(
                PermissionMiddleware::using(['transaction-create']),
                only: ['transaction']
            ),

            new Middleware(
                PermissionMiddleware::using(['transaction-edit']),
                only: ['update', 'updateVerifiedStatus']
            ),

            new Middleware(
                PermissionMiddleware::using(['transaction-delete']),
                only: ['destroy']
            ),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $transactions = $this->transactionRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data Transaction Berhasil Diambil', TransactionResource::collection($transactions), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getAllPaginated(Request $request)
    {
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer'

        ]);

        try {
            $transactions = $this->transactionRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'] ?? null
            );
            return ResponseHelper::jsonResponse(true, 'Data Transaction Berhasil Diambil', PaginateResource::make($transactions, TransactionResource::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $transaction = $this->transactionRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Transaksi Berhasil Ditambahkan', new TransactionResource($transaction), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        try {
            $transactions = $this->transactionRepository->getById($id);

            if (!$transactions) {
                return ResponseHelper::jsonResponse(true, 'Data Transaksi Tidak Ditemukan', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'Data Transaksi Berhasil Diambil', new TransactionResource($transactions), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function showByCode(string $code)
    {

        try {
            $transactions = $this->transactionRepository->getByCode($code);

            if (!$transactions) {
                return ResponseHelper::jsonResponse(true, 'Data Transaksi Tidak Ditemukan', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'Data Transaksi Berhasil Diambil', new TransactionResource($transactions), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionUpdateRequest $request, string $id)
    {

        $request = $request->validated();
        try {
            $transaction = $this->transactionRepository->getById($id);

            if (!$transaction) {
                return ResponseHelper::jsonResponse(true, 'Data Transaksi Tidak Ditemukan', null, 404);
            }
            $transaction = $this->transactionRepository->updateStatus(
                $id,
                $request
            );
            return ResponseHelper::jsonResponse(true, 'Data Transaksi Berhasil Diambil', new TransactionResource($transaction), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $transaction = $this->transactionRepository->getById($id);

            if (!$transaction) {
                return ResponseHelper::jsonResponse(true, 'Data Transaksi Tidak Ditemukan', null, 404);
            }
            $transaction = $this->transactionRepository->delete(
                $id,

            );
            return ResponseHelper::jsonResponse(true, 'Data Transaksi Berhasil Dihapus', new TransactionResource($transaction), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
