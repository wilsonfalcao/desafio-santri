<?php

declare(strict_types=1);

namespace App\Http\Controllers;

// Models
use App\Http\Requests\StoreProductRequest;
// Mock
use App\Models\Product;
//
// Rules Strategy Calculate
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //

    public function index(Request $request) {}

    public function store(StoreProductRequest $request)
    {
        $post = Product::create($request->validated());

        return response()->json($post->id, 201);
    }
}
