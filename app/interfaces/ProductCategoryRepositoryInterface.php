<?php

namespace App\interfaces;

interface ProductCategoryRepositoryInterface
{
    public function getAll(
        ?string $search = null,
        ?bool $isParent = null,
        ?int $limit = null,
        bool $execute = false
    );

    public function getAllPaginated(
        ?string $search = null,
        ?bool $isParent = null,
        ?int $rowPerPage = null
    );

    public function getById(
        string $id
    );

    public function create(
        array $data
    );
}
