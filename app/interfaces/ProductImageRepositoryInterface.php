<?php

interface ProductImageRepositoryInterface
{
    public function create(
        array $date
    );

    public function delete(
        string $id
    );
}