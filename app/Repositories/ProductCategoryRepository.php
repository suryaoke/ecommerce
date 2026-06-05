<?php

namespace App\Repositories;

use App\interfaces\ProductCategoryRepositoryInterface;

use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{

    public function getAll(
        ?string $search = null,
        ?bool $isParent = null,
        ?int $limit = null,
        bool $execute = false
    ) {
        $query = ProductCategory::with(['children', 'parent'])
            ->where(function ($query) use ($isParent, $search) {
                if ($search) {
                    $query->search($search);
                }

                if ($isParent === true) {
                    if ($search) {
                        $query->whereNull('parent_id');
                    }
                }
            });

        if ($isParent !== null) {
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
        ?bool $isParent = null,
        ?int $rowPerPage = null
    ) {
        $query = $this->getAll($search, $isParent, null, false);

        return $query->paginate($rowPerPage);
    }

    public function getById(
        string $id
    ) {
        $query = ProductCategory::where('id', $id);

        return $query->first();
    }

    public function create(
        array $data
    ) {
        DB::beginTransaction();

        try {
            $productCategory = new ProductCategory;
            if (isset($data['parent_id'])) {
                $productCategory->parent_id = $data['parent_id'];
            }
            if (isset($data['image'])) {
                $productCategory->image = $data['image']->store('assets/product-category', 'public');
            }
            $productCategory->name = $data['name'];
            $productCategory->slug = Str::slug($data['name']);
            $productCategory->description = $data['description'];
            
            if (isset($data['tagline'])) {
                $productCategory->tagline = $data['tagline'];
            }
            $productCategory->save();

            DB::commit();

            return $productCategory;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }
}
