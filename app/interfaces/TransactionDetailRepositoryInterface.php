<?php

namespace App\interfaces;

interface TransactionDetailRepositoryInterface
{
    public function create(
        array $data
    );
}