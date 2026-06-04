<?php

namespace App\Repositories;

use App\interfaces\ProductCategoryRepositoryInterface;

use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Facades\DB;

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

                if($isParent === true){
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

}
