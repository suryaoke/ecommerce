<?php

namespace App\interfaces;

interface WithdrawalRepositoryInterface
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
    public function create(
        array $data
    );
}
