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

    public function update(
        string $id,
        array $data
    );

    public function delete(
        string $id
    );
}
