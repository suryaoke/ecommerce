<?php

namespace App\interfaces;

interface AuthRepositoryInterface
{
    public function register(
        array $data
    );

    public function login(
        array $data
    );

    public function me();
    public function logout();
}
