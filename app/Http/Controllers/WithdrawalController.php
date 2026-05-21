<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\WithdrawalResoure;
use App\interfaces\WithdrawalRepositoryInterface;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    private WithdrawalRepositoryInterface $withdrawalRepository;

    public function __construct(WithdrawalRepositoryInterface $withdrawalRepository)
    {
        $this->withdrawalRepository = $withdrawalRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $withdrawals = $this->withdrawalRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data Withdrawal Berhasil Diambil', WithdrawalResoure::collection($withdrawals), 200);
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
            $withdrawals = $this->withdrawalRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'] ?? null
            );
            return ResponseHelper::jsonResponse(true, 'Data Withdrawal Berhasil Diambil', PaginateResource::make($withdrawals, WithdrawalResoure::class), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
