<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\BuyerResource;
use App\Http\Resources\PaginateResource;
use App\interfaces\BuyerRepositoryInterface;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private BuyerRepositoryInterface $buyerRepository;

    public function __construct(BuyerRepositoryInterface $buyerRepository)
    {
        $this->buyerRepository = $buyerRepository;
    }


    public function index(Request $request)
    {
        try {
            $buyers = $this->buyerRepository->getAll(
                $request->search,
                $request->limit,
                true
            );
            return ResponseHelper::jsonResponse(true, 'Data Pembeli Berhasil Diambil', BuyerResource::collection($buyers), 200);
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
            $buyers = $this->buyerRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page'] ?? null
            );
            return ResponseHelper::jsonResponse(true, 'Data Buyer Berhasil Diambil', PaginateResource::make($buyers, BuyerResource::class), 200);
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

        try {
            $buyer = $this->buyerRepository->getById($id);

            if (!$buyer) {
                return ResponseHelper::jsonResponse(true, 'Data Pembeli Tidak Ditemukan', null, 404);
            }
            return ResponseHelper::jsonResponse(true, 'Data Pembeli Berhasil Diambil', new BuyerResource($buyer), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
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
