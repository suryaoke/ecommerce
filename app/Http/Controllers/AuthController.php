<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\RegisterStoreRequest;
use App\Http\Resources\UserResource;
use App\interfaces\AuthRepositoryInterface;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $user = $this->authRepository->register($request);

            return ResponseHelper::jsonResponse(
                true,
                'Registrasi Berhasil',
               new UserResource($user),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }
}
