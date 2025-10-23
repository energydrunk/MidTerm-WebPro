<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\Review;

class CatalogController extends Controller
{
    private array $labels = [
        'pastry'      => 'Pastry',
        'cake'        => 'Cake',
        'bread'       => 'Bread',
        'dessert_box' => 'Dessert Box',
    ];

    public function home(Request $request)
    {
        $q = trim((string)$request->query('q', ''));

        $products = Product::query()
            ->where('is_active', true)
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $avgRating    = round((float) (Review::avg('rating') ?? 0), 2);
        $totalReviews = (int) (Review::count() ?? 0);

        $reviews = Review::with('user')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('home', compact('products', 'avgRating', 'totalReviews', 'reviews', 'q'));
    }

    public function index(Request $request, string $category)
    {
        if ($category === 'dessert-box') $category = 'dessert_box';

        $q = trim((string)$request->query('q', ''));
        $title = $this->labels[$category] ?? ucfirst(str_replace('_',' ',$category));

        $products = Product::query()
            ->where('is_active', true)
            ->where('category', $category)
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                       ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->orderBy('created_at','desc')
            ->paginate(12)
            ->withQueryString();

        return view('catalog.index', [
            'category' => $category,
            'title'    => $title,
            'products' => $products,
            'labels'   => $this->labels,
            'q'        => $q,
        ]);
    }

    public function storeReview(Request $request)
    {
        $this->middleware('auth');

        $data = $request->validate([
            'rating'  => ['required', 'integer', Rule::in([1,2,3,4,5])],
            'comment' => ['nullable', 'string', 'max:600'],
        ]);

        Review::create([
            'user_id' => $request->user()->id,
            'rating'  => (int) $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return back()->with('info', 'Thank you! Your review has been submitted.');
    }
}
