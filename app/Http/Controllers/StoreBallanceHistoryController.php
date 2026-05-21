<?php

namespace App\Http\Controllers;

use App\interfaces\StoreBallanceHistoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Http\Resources\StoreBalanceHistoryResource;
use App\Http\Resources\PaginateResource;

class StoreBallanceHistoryController extends Controller
{
    private StoreBallanceHistoryRepositoryInterface $storeBalanceHistoryRepository;

    public function __construct(StoreBallanceHistoryRepositoryInterface $storeBalanceHistoryRepository)
    {
        $this->storeBalanceHistoryRepository = $storeBalanceHistoryRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $storeBalanceHistories = $this->storeBalanceHistoryRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data Riwayat Dompet Toko Berhasil Diambil', StoreBalanceHistoryResource::collection($storeBalanceHistories), 200);
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
            $storeBalanceHistories = $this->storeBalanceHistoryRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'] ?? null
            );
            return ResponseHelper::jsonResponse(true, 'Data Riyawat Dompet Toko Berhasil Diambil', PaginateResource::make($storeBalanceHistories, StoreBalanceHistoryResource::class), 200);
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
            $storeBalanceHistory = $this->storeBalanceHistoryRepository->getById($id);

            if (!$storeBalanceHistory) {
                return ResponseHelper::jsonResponse(true, 'Data Riyawat Dompet Toko Tidak Ditemukan', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'Data Riyawat Dompet Toko Berhasil Diambil', new StoreBalanceHistoryResource($storeBalanceHistory), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

}
