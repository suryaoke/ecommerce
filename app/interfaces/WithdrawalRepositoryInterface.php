<?php

namespace App\interfaces;

use GuzzleHttp\Psr7\UploadedFile;

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

    public function approve(
        string $id,
        UploadedFile $approve
    );
}
