<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    
    public function getSale()
    {
        return response()->json(Sale::all(), Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        // --- IGNORE ---
        return response()->json(['message' => 'Create Sale']);
    }
}
