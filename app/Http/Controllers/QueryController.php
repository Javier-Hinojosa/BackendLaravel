<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    public function get()
    {
    $products = Product::all();

    return response()->json($products);
}

    public function getById(int $id)
    {
    $product = Product::find($id);
    if ($product) {
        return response()->json($product);
    } else {
        return response()->json(['message' => 'Product not found'], 404);
    }
}

    public function getNames()
    {
    $names = Product::select('name')
            ->orderBy('name', 'desc')
    ->get();

    return response()->json($names);
    }

public function getSearch(string $name, float $price){

    $products = Product::where('name','like',"%{$name}%")
    ->orWhere('description','like',"%{$name}%")
    ->where('price', '>', $price)
    ->select('name', 'price', 'description')
    ->orderBy('description', 'asc')
    ->get();

    return response()->json($products);
}

public function advancedSearch(Request $request)
{
    $products = Product::where(function($query) use ($request) {
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%')
                  ->orWhere('description', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }
    })->get();

    return response()->json($products);
}

public function join(){
    $products = Product::join('category', 'product.category_id', '=', 'category.id')
    ->select('category.id', 'category.name', DB:: raw("COUNT(product.id) as total"))
    ->groupBy("category.id", "category.name")
    ->get();

    return response()->json($products);

}



}
