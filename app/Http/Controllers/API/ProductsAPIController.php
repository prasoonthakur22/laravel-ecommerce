<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Validator;

class ProductsAPIController extends AppBaseController
{
    public function index()
    {
        $productCount = Product::all()->count();
        if (!$productCount) return $this->sendError('Products not found', 404);

        $products = Product::all();

        return $this->sendResponse($products, 'Products retrieved successfully');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:50',
            'description' => 'required|max:255',
            'price' => 'required|integer|between:1,90000',
            'quantity' => 'required|integer|between:1,10000'
        ]);

        if ($validator->fails()) return $this->sendError($validator->errors()->first(), 400);

        $validated = $validator->validated();

        $product =  Product::create($validated);

        if (!$product) return $this->sendError('Product does not created');

        return $this->sendResponse($product, 'Product created successfully');
    }


    public function show($id)
    {
        /** @var Product $user */

        $productCount = Product::where('id', '=', $id)->count();

        $product = Product::where('id', '=', $id)->get();

        if (!$productCount) return $this->sendError('Product not found', 404);

        return $this->sendResponse($product, 'Product retrieved successfully');
    }


    public function update($id, Request $request)
    {
        $productCount = Product::where('id', '=', $id)->count();

        if (!$productCount) return $this->sendError('Product not found', 404);

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|max:50',
            'description' => 'nullable|max:255',
            'price' => 'required|integer|between:1,90000',
            'quantity' => 'required|integer|between:1,10000'
        ]);

        if ($validator->fails()) return $this->sendError($validator->errors()->first(), 400);

        $validated = $validator->validated();

        $product = Product::where('id', '=', $id);

        $product = $product->update($validated);

        if (!$product) return $this->sendError('Product does not updated');

        return $this->sendResponse($product, 'Product updated successfully');
    }



    public function destroy($id)
    {
        /** @var Product $user */
        $product = Product::where('id', '=', $id);

        $productCount = Product::where('id', '=', $id)->count();

        if (!$productCount) return $this->sendError('Product not found', 404);

        $product->delete();

        return $this->sendSuccess('Product deleted successfully');
    }
}
