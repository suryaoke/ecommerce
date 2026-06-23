<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProductReviewStoreRequest;
use App\Http\Requests\ProductReviewUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\ProductReviewResource;
use App\interfaces\ProductReviewRepositoryInterface;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    private ProductReviewRepositoryInterface $productReviewRepository;

    public function __construct(ProductReviewRepositoryInterface $productReviewRepository)
    {
        $this->productReviewRepository = $productReviewRepository;
    }

    public function store(ProductReviewStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $productReview = $this->productReviewRepository->create($request);

            return ResponseHelper::jsonResponse(true, 'Review Berhasil Ditambahkan', new ProductReviewResource($productReview), 201);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

}
