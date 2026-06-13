<?php

namespace App\interfaces;

interface TransactionRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $exceute
    );

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    );

    public function getById(
        string $id
    );

    public function getByCode(
        string $code
    );

    public function create(
        array $data
    );
}
