<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class SellerProductController extends Controller
{
    public function index()
    {
        $products = Product::where('user_id', auth()->id())->latest()->get();
        return view('seller.dashboard', compact('products'));
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name' => 'required|string|max:120',
            'price' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'category' => 'required|in:pastry,cake,bread,dessert_box',
        ]);

        $data['user_id'] = auth()->id();
        $data['stock'] = $data['stock'] ?? 0;

        Product::create($data);
        return back()->with('info', 'Product added');
    }

    public function update(Request $req, Product $product)
    {
        abort_unless($product->user_id === auth()->id(), 403);

        $data = $req->validate([
            'name' => 'required|string|max:120',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category' => 'required|in:pastry,cake,bread,dessert_box',
        ]);

        $product->update($data);
        return back()->with('info', 'Product updated');
    }

    public function destroy(Product $product)
    {
        abort_unless($product->user_id === auth()->id(), 403);
        $product->delete();
        return back()->with('info', 'Product deleted');
    }
}
