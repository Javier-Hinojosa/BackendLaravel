<?php

use App\Business\Services\CreateSaleService;
use App\Http\Requests\SaleRequest;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
   $this->service= new CreateSaleService();
});

uses(RefreshDatabase::class);

test('Creación de venta correctamente', function () {
    $product1 = Product::factory()->create([
        'price' => 10,
    ]);
    $product2 = Product::factory()->create([
        'price' => 20,
    ]);

    $request = new SaleRequest([
        'email' => 'test@example.com',
        'sale_date' => '2024-06-01 12:00:00',
        'concepts' => [
            [
                'product_id' => $product1->id, 
                'quantity' => 2,//2 * 10 = 20
            ],
            [
                'product_id' => $product2->id, 
                'quantity' => 3,//3 * 20 = 60
            ],
        ],
    ]);

    $saleEntity = $this->service->create($request);

    $this->assertDatabaseHas('sale', [
        'id' => $saleEntity->id,
        'email' => 'test@example.com',
        'sale_date' => '2024-06-01 12:00:00',
        'total' => 80, // 20 + 60
    ]);

    $this->assertDatabaseHas('concept', [
        'sale_id' => $saleEntity->id,
        'product_id' => $product1->id,
        'quantity' => 2,
        'price' => 10,
    ]);

    $this->assertDatabaseHas('concept', [
        'sale_id' => $saleEntity->id,
        'product_id' => $product2->id,
        'quantity' => 3,
        'price' => 20,
    ]);

});

test('Falla de validación de venta', function () {
    $data =[
        'email' => '',
        'sale_date' => '',
        'concepts' => []
    ];

    $validator= Validator::make($data, (new SaleRequest())->rules());
    expect($validator->fails())->toBeTrue();
});
