<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SellerController extends Controller
{

    public function dashboard()
    {
        $sellerId = auth()->id();

        $products = Product::where('user_id', $sellerId)
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        $summary = [
            'items_count' => OrderItem::whereHas('product', fn($q) => $q->where('user_id', $sellerId))->count(),
            'revenue'     => (float) OrderItem::whereHas('product', fn($q) => $q->where('user_id', $sellerId))->sum('subtotal'),
        ];

        $rating = [
            'avg'   => round((float) Review::avg('rating'), 2),
            'count' => (int) Review::count(),
        ];

        return view('seller.dashboard', compact('products','summary','rating'));
    }

    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required','string','max:150'],
            'price'       => ['required','numeric','min:0'],
            'category'    => ['required', Rule::in(['pastry','cake','bread','dessert_box','dessert-box'])],
            'stock'       => ['nullable','integer','min:0'],
            'description' => ['nullable','string'],
            'image_url'   => ['nullable','string','max:255'],
        ]);

        $data = $this->trimStrings($data);

        $data['category'] = $this->normalizeCategory($data['category']);

        if (($data['image_url'] ?? '') === '') $data['image_url'] = null;

        $data['stock'] = (int) ($data['stock'] ?? 0);

        $data['user_id']   = auth()->id();
        $data['is_active'] = true;

        Product::create($data);

        return back()->with('info','Product added!');
    }

    public function updateProduct(Request $request, int $id)
    {
        $product = Product::findOrFail($id);

        if (
            strcasecmp(auth()->user()->email ?? '', 'adminbakery@gmail.com') !== 0 &&
            $product->user_id !== auth()->id()
        ) {
            return redirect()->route('seller.dashboard')->with('info','Not allowed.');
        }

        $data = $request->validate([
            'name'        => ['required','string','max:150'],
            'price'       => ['required','numeric','min:0'],
            'category'    => ['required', Rule::in(['pastry','cake','bread','dessert_box','dessert-box'])],
            'stock'       => ['nullable','integer','min:0'],
            'description' => ['nullable','string'],
            'image_url'   => ['nullable','string','max:255'],
            'is_active'   => ['sometimes','boolean'],
        ]);

        $data = $this->trimStrings($data);
        $data['category'] = $this->normalizeCategory($data['category']);
        if (($data['image_url'] ?? '') === '') $data['image_url'] = null;
        if (array_key_exists('stock', $data)) {
            $data['stock'] = max(0, (int) $data['stock']); 
        }

        $product->update($data);

        return back()->with('info','Product updated!');
    }

    public function destroyProduct(int $id)
    {
        $p = Product::findOrFail($id);

        if (
            strcasecmp(auth()->user()->email ?? '', 'adminbakery@gmail.com') !== 0 &&
            $p->user_id !== auth()->id()
        ) {
            return redirect()->route('seller.dashboard')->with('info','Not allowed.');
        }

        $p->delete();
        return back()->with('info','Product removed.');
    }

    public function category(string $category)
    {
        $labels = [
            'pastry'      => 'Pastry',
            'cake'        => 'Cake',
            'bread'       => 'Bread',
            'dessert_box' => 'Dessert Box',
        ];

        $category = $this->normalizeCategory($category);
        $title    = $labels[$category] ?? ucfirst(str_replace('_',' ',$category));

        $products = Product::where('user_id', auth()->id())
            ->where('category', $category)
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('seller.category', [
            'category' => $category,
            'title'    => $title,
            'products' => $products,
            'labels'   => $labels,
        ]);
    }

    public function orders(Request $request)
    {
        $sellerId = auth()->id();

        $items = OrderItem::with(['order','product'])
            ->whereHas('product', fn($q) => $q->where('user_id', $sellerId))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'items_count' => OrderItem::whereHas('product', fn($q) => $q->where('user_id', $sellerId))->count(),
            'revenue'     => (float) OrderItem::whereHas('product', fn($q) => $q->where('user_id', $sellerId))->sum('subtotal'),
        ];

        return view('seller.orders', compact('items','summary'));
    }

    private function normalizeCategory(string $cat): string
    {
        return $cat === 'dessert-box' ? 'dessert_box' : $cat;
    }

    private function trimStrings(array $data): array
    {
        foreach ($data as $k => $v) {
            if (is_string($v)) $data[$k] = trim($v);
        }
        return $data;
    }
}
