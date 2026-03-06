<?php

namespace App\Http\Controllers;

use App\Business\Interfaces\CreateSaleInterface;
use App\Business\Services\CreateSaleService;
use App\Http\Requests\SaleRequest;
use App\Models\Sale;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    public function __construct(protected CreateSaleService $createSaleService) {}

    public function getSale()
    {
        return response()->json(Sale::all(), Response::HTTP_OK);
    }

    public function create(SaleRequest $request)
    {
        try {
            $saleEntity = $this->createSaleService->create($request);

            return response()->json($saleEntity, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error creating sale: '.$e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
