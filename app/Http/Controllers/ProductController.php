<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::latest()->paginate(5);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'price' => 'required|integer',
            'color' => 'required|string',
            'condition' => 'required|in:new,old',
            'description' => 'string',
        ]);

        $product = Product::create($request->all());

        return response()->json($product);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'price' => 'required|integer',
            'color' => 'required|string',
            'condition' => 'required|in:new,old',
            'description' => 'string',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return response()->json($product);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json([
            'message' => 'product deleted'
        ]);
    }
}
