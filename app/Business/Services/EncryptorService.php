<?php

namespace App\Business\Services;

use Illuminate\Support\Facades\Crypt;

class EncryptorService
{
    private string $key;
    
    public function __construct(string $key) {
         $this->key = $key;
         }

    public function encrypt(string $data): string {
        return base64_encode(
            $this->key .
             ":". 
            Crypt::encryptString($data));
    }
    
    public function decrypt(string $encryptedData): string {
        $decoded = base64_decode($encryptedData);
        [$key, $encrypted] = explode(":", $decoded, 2);
        
        if ($key !== $this->key) {
            throw new \InvalidArgumentException("Invalid encrypted data");
        }
        
        return Crypt::decryptString($encrypted);
    }



}