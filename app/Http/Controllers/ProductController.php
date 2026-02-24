<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $page = $request->query('page', 0);
        $offset = $page * $perPage;

        $products = Product::skip($offset)->take($perPage)->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        try {
            $validateData = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
                'category_id' => 'required|integer|exists:category,id',
            ],
            [
                'name.required' => 'El nombre del producto es obligatorio.',
                'name.string' => 'El nombre del producto debe ser una cadena de texto.',
                'name.max' => 'El nombre del producto no puede exceder los 255 caracteres.',
                'price.required' => 'El precio del producto es obligatorio.',
                'price.numeric' => 'El precio del producto debe ser un número.',
                'category_id.required' => 'La categoría del producto es obligatoria.',
                'category_id.exists' => 'La categoría especificada no existe.',
            ]);

            $product = Product::create($validateData);

            return response()->json($product, 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        try{
        $validateData = $request->validated();
        $product->update($validateData);
        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'product' => $product,
        ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
       
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Producto eliminado correctamente',
        ]);
    }
}
