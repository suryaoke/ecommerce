<?php

namespace App\Repositories;

use App\interfaces\AuthRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthRepository implements AuthRepositoryInterface
{

    public function register(array $data)
    {
        DB::beginTransaction();

        try {
            $user = new User;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            $user->assignRole($data['role']);

            if ($data['role'] == 'buyer') {
                $user->buyer()->create([
                    'profile_picture' => null,
                    'phone_number' => null,
                ]);
            }

           $user->token = $user->createToken('auth_token')->plainTextToken;

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new \Exception($e->getMessage());
        }
    }
}
