<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

// crea una prueba de ejemplo para el controlador de productos
uses(RefreshDatabase::class);

test('example', function () {
    // crea 10 productos en la base de datos
    Product::factory()->count(10)->create();

    $response = $this->getJson('/api/product?per_page=5&page=0');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(5)
        ->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'description',
                'price',
                'category_id',
            ],
        ]);
    
        $data = $response->json();
        expect(count($data))->toBe(5);
});

test('Crear producto de manera exitosa', function () {
    $category = Category::factory()->create();
    $productData = [
        'name' => 'Producto de prueba',
        'description' => 'Descripción del producto de prueba',
        'price' => 99.99,
        'category_id' => $category->id,
    ];
    
    $response = $this->postJson(route('product.store'), $productData);
    $response->assertStatus(Response::HTTP_CREATED)
        ->assertJson($productData);

    $this->assertDatabaseHas('product', $productData);
 });

 test("Datos de producto inválidos", function () {
    $invalidProductData = [
        'name' => '', // Nombre vacío
        'price' => 'not-a-number', // Precio no numérico
        'description' => 35, // Descripción no es una cadena de texto
        'category_id' => 99569, // ID de categoría que no existe
    ];

    $response = $this->postJson(route('product.store'), $invalidProductData);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['name', 'price', 'description', 'category_id']);
 });

 test("Actualizar producto de manera exitosa", function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
    ]);

    $newCategory = Category::factory()->create();

    $updateData = [
        'name' => 'Producto actualizado',
        'description' => 'Descripción actualizada',
        'price' => 149.99,
        'category_id' => $newCategory->id,
    ];

    $response = $this->putJson(route('product.update', $product), $updateData);
    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonFragment([
                'id' => $product->id,
                'name' => $updateData['name'],
                'description' => $updateData['description'],
                'price' => $updateData['price'],
                'category_id' => $updateData['category_id']
        ]);

    $this->assertDatabaseHas('product', [
        'id' => $product->id,
        'name' => $updateData['name'],
        'description' => $updateData['description'],
        'price' => $updateData['price'],
        'category_id' => $updateData['category_id'],
    ]);
 });