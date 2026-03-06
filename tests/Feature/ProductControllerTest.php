<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\Authenticate;

// crea una prueba de ejemplo para el controlador de productos
uses(RefreshDatabase::class);

beforeEach(function () {
    // Crea un usuario y genera un token JWT para autenticación
   // $user = User::factory()->create();
   // $this->token = JWTAuth::fromUser($user);
   $this->withoutMiddleware(Authenticate::class); // Desactiva los middlewares para las pruebas
});

test('Obtener productos paginados', function () {
    $user = User::factory()->create();
    $token = JWTAuth::fromUser($user);

    // crea 10 productos en la base de datos
    Product::factory()->count(10)->create();

    $response = $this
    ->withHeaders([
        'Authorization' => "Bearer $token",
    ])
    ->getJson('/api/product?per_page=5&page=0');

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
        ->assertJsonFragment([ // solo verificamos los campos actualizados
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

test("Falla sino se envia category_id", function () { 
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
    ]);

   // dd($category->id); // 1

     $data = [
        'name' => 'Producto actualizado',
        'description' => 'Descripción actualizada',
        'price' => 149.99,
        'category_id' => null, // No se envía category_id
    ];

    $response = $this->putJson(route('product.update', $product), $data);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['category_id']);

});

test("Falla si category_id no existe en la base de datos", function () { 
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id,
    ]);

     $data = [
        'name' => 'Producto actualizado',
        'description' => 'Descripción actualizada',
        'price' => 149.99,
        'category_id' => 99999, // category_id que no existe
    ];

    $response = $this->putJson(route('product.update', $product), $data);
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['category_id']);

});

test("Eliminar producto de manera exitosa", function () {
    $product = Product::factory()->create();

    $response = $this->deleteJson(route('product.destroy',$product));
    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'message' => 'Producto eliminado correctamente',
        ]);

  /*  $this->assertDatabaseMissing('product', [
        'id' => $product->id,
    ]);*/
    $this->assertSoftDeleted('product', [
        'id' => $product->id,
    ]);
});

test("Falla al eliminar producto que no existe", function () {
    $response = $this->deleteJson(route('product.destroy', ["product" => 99999])); // ID de producto que no existe
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});