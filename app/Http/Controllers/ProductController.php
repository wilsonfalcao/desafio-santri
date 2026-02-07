<?php

declare(strict_types=1);

namespace App\Http\Controllers;

// Models
use App\Http\Requests\StoreProductRequest;
// Mock
use App\Models\Product;

class ProductController extends Controller
{
    public function store(StoreProductRequest $request)
    {
        $post = Product::create($request->validated());

        return response()->json($post->id, 201);
    }
}
