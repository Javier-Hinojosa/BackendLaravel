<?php

namespace App\Business\Services;

use App\Business\Entities\ConceptEntity;
use App\Business\Entities\SaleEntity;
use App\Http\Requests\SaleRequest;
use App\Models\Concept;
use App\Models\Product;
use App\Models\Sale;

class CreateSaleService
{
    public function create(SaleRequest $request)
    {
        $conceptsEntities = [];
        foreach ($request->concepts as $concept) {
            $conceptsEntities[] = new ConceptEntity(
                $concept['quantity'],
                Product::find($concept['product_id'])->price,
                $concept['product_id']
            );
        }
        $saleEntity = new SaleEntity(
            null,
            $request->email,
            $request->sale_date,
            $conceptsEntities
        );

        $sale = Sale::create([
            'email' => $saleEntity->email,
            'sale_date' => $saleEntity->sale_date,
            'total' => $saleEntity->total,
        ]);
        $sale->save();

        foreach ($conceptsEntities as $conceptEntity) {
            $concept = Concept::create([
                'quantity' => $conceptEntity->quantity,
                'price' => $conceptEntity->price,
                'product_id' => $conceptEntity->product_id,
                'sale_id' => $sale->id,
            ]);
            $concept->save();
        }
        $saleEntity->id = $sale->id;

        return $saleEntity;
    }
}
