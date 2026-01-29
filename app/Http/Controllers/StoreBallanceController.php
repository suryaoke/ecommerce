<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreBallanceResource;
use App\interfaces\StoreBallanceRepositoryInterface;
use Illuminate\Http\Request;

class StoreBallanceController extends Controller
{
    private StoreBallanceRepositoryInterface $storeBallanceRepository;

    public function __construct(StoreBallanceRepositoryInterface $storeBallanceRepository)
    {
        $this->storeBallanceRepository = $storeBallanceRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $storeBallances = $this->storeBallanceRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data Dompet Toko Berhasil Diambil', StoreBallanceResource::collection($storeBallances), 200);
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
            $storeBallances = $this->storeBallanceRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'] ?? null
            );
            return ResponseHelper::jsonResponse(true, 'Data Dompet Toko Berhasil Diambil', PaginateResource::make($storeBallances, StoreBallanceResource::class), 200);
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
            $storeBallance = $this->storeBallanceRepository->getById($id);

            if (!$storeBallance) {
                return ResponseHelper::jsonResponse(true, 'Data Dompet Toko Tidak Ditemukan', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'Data Dompet Toko Berhasil Diambil', new StoreBallanceResource($storeBallance), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }


}
