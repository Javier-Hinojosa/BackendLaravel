<?php

namespace App\Http\Controllers;

use App\Business\Services\EncryptorService;
use App\Business\Services\ProductServices;
use App\Business\Services\SingletonService;
use App\Business\Services\UserService;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InfoController extends Controller
{
    public function __construct(protected ProductServices $productServices,
                                protected EncryptorService $encryptorService,
                                protected UserService $userService,
                                protected SingletonService $singletonService
                                ) {

    }

    public function iva(int $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'error' => 'Producto no encontrado',
            ], Response::HTTP_NOT_FOUND);
        }
        $priceWithIVA = $this->productServices->calculateIVA($product->price);

        return response()->json([
            'name' => $product->name,
            'price' => $product->price,
            'price_with_iva' => $priceWithIVA,
        ], Response::HTTP_OK);

    }

    public function encrypt($data)
    {
        return response()->json([
            'original_data' => $data,
            'encrypted_data' => $this->encryptorService->encrypt($data),
        ], Response::HTTP_OK);
    }

    public function decrypt($data)
    {
        return response()->json([
            'encrypted_data' => $data,
            'decrypted_data' => $this->encryptorService->decrypt($data),
        ], Response::HTTP_OK);
    }

    public function encryptUserEmail(int $id)
    {
        try {
            $encryptedEmail = $this->userService->encryptEmail($id);
            return response()->json([
                'user_id' => $id,
                'encrypted_email' => $encryptedEmail,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function singletonValue(SingletonService $singletonService2)
    {
        return response()->json([
            'singleton_value_1' => $this->singletonService->value,
            'singleton_value_2' => $singletonService2->value,
        ], Response::HTTP_OK);

    }

        public function encryptUserEmail2(int $id)
    {
        try {
            $userService = app()->make(UserService::class);
            
            $encryptedEmail = $userService->encryptEmail($id);
            return response()->json([
                'user_id' => $id,
                'encrypted_email' => $encryptedEmail,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
    }


}
