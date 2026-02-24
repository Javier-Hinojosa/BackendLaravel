<?php

namespace App\Business\Services;

use App\Models\User;

class UserService
{
    public function __construct(protected EncryptorService $encryptorService) {

    }

    public function encryptEmail(string $id): string
    {
        $user = User::find($id);
        if (!$user) {
            throw new \Exception("User not found");
        }
        return $this->encryptorService->encrypt($user->email);
    }

}