<?php

namespace App\Repositories;

use App\interfaces\ProductRepositoryInterface;

use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductRepository implements ProductRepositoryInterface
{

    public function getAll(
        ?string $search = null,
        ?string $productCategoryId = null,
        ?int $limit = null,
        bool $execute = false
    ) {
        $query = Product::where(function ($query) use ($productCategoryId, $search) {
            if ($search) {
                $query->search($search);
            }

            if ($productCategoryId === true) {
                if ($search) {
                    $query->where('product_category_id', $productCategoryId);
                }
            }
        })->with('productImage');

        if ($productCategoryId !== null) {
            $query->whereNull('parent_id');  // filter hanya parent
        }

        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(
        ?string $search = null,
        ?string $productCategoryId = null,
        ?int $rowPerPage = null
    ) {
        $query = $this->getAll($search, $productCategoryId, null, false);

        return $query->paginate($rowPerPage);
    }

    public function getById(
        string $id
    ) {
        $query = Product::where('id', $id)->with('productImage');

        return $query->first();
    }

    public function getBySlug(
        string $slug
    ) {
        $query = Product::where('slug', $slug)->with('productImage');

        return $query->first();
    }
}
