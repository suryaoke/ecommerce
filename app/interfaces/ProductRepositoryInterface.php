<?php

namespace App\interfaces;

interface ProductRepositoryInterface
{
    public function getAll(
        ?string $search = null,
        ?string $productCategoryId = null,
        ?int $limit = null,
        bool $execute = false
    );

    public function getAllPaginated(
        ?string $search = null,
        ?string $productCategoryId = null,
        ?int $rowPerPage = null
    );

    public function getById(
        string $id
    );

    public function getBySlug(
        string $id
    );

    public function create(
        array $data
    );
}
