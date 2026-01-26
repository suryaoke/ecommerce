<?php

namespace App\interfaces;

interface StoreRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?bool $isVerified,
        ?int $limit,
        bool $exceute
    );

    public function getAllPaginated (
        ?string $search,
        ?bool $isVerified,
        ?int $rowPerPage
    );
}