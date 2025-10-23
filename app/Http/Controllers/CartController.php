<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;

class CartController extends Controller
{

    public function index()
    {
        $items = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $total = $items->reduce(fn ($c, $it) => $c + ((float) $it->price * (int) $it->quantity), 0.0);

        return view('cart.index', compact('items', 'total'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty'        => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        if (!$product->is_active) {
            return back()->with('info', 'Produk tidak aktif.');
        }

        $inCartNow = (int) CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->value('quantity') ?? 0;

        $want = (int) $data['qty'];
        $available = max(0, (int) $product->stock - $inCartNow);

        if ($available <= 0) {
            return back()->with('info', 'Stok habis. Tidak bisa menambahkan ke cart.');
        }
        if ($want > $available) {
            $want = $available;
        }

        $item = CartItem::firstOrCreate(
            ['user_id' => auth()->id(), 'product_id' => $product->id],
            ['quantity' => 0, 'price' => $product->price]
        );

        $item->quantity += $want;
        $item->price     = $product->price; 
        $item->save();

        $count = CartItem::where('user_id', auth()->id())->sum('quantity');
        session(['cart_count' => (int) $count]);

        $msg = ($want < (int)$request->qty)
            ? "Sebagian ditambahkan ($want). Sisa stok tidak mencukupi."
            : 'Produk ditambahkan ke cart.';

        return back()->with('info', $msg);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'qty' => ['required', 'integer', 'min:1'],
        ]);

        $item = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->firstOrFail();

        $reqQty = (int) $data['qty'];
        $maxQty = max(0, (int) $product->stock); 

        if ($reqQty > $maxQty) {
            $item->quantity = $maxQty;
            $item->save();

            $count = CartItem::where('user_id', auth()->id())->sum('quantity');
            session(['cart_count' => (int) $count]);

            return back()->with('info', "Jumlah melebihi stok. Diset ke $maxQty.");
        }

        $item->quantity = $reqQty;
        $item->save();

        $count = CartItem::where('user_id', auth()->id())->sum('quantity');
        session(['cart_count' => (int) $count]);

        return back()->with('info', 'Cart diperbarui.');
    }

    public function remove(Product $product)
    {
        CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        $count = CartItem::where('user_id', auth()->id())->sum('quantity');
        session(['cart_count' => (int) $count]);

        return back()->with('info', 'Item dihapus dari cart.');
    }

    public function clear()
    {
        CartItem::where('user_id', auth()->id())->delete();
        session(['cart_count' => 0]);

        return back()->with('info', 'Cart dikosongkan.');
    }

    public function confirm()
    {
        $items = CartItem::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('cart')->with('info', 'Cart kosong.');
        }

        $insufficient = [];
        foreach ($items as $it) {
            $p = $it->product;
            if (!$p || !$p->is_active || (int)$p->stock < (int)$it->quantity) {
                $insufficient[] = optional($p)->name ?? "Produk #{$it->product_id}";
            }
        }
        if (!empty($insufficient)) {
            return redirect()->route('cart')
                ->with('info', 'Beberapa item kehabisan/kurang stok: ' . implode(', ', $insufficient));
        }

        $total = $items->reduce(fn ($c, $it) => $c + ((float) $it->price * (int) $it->quantity), 0.0);

        return view('cart.checkout', compact('items', 'total'));
    }

    public function checkout(Request $request)
    {
        $request->validate(['confirm' => ['accepted']]);

        $userId = auth()->id();

        $items = CartItem::with('product')
            ->where('user_id', $userId)
            ->get();

        if ($items->isEmpty()) {
            return back()->with('info', 'Cart kosong.');
        }

        $total = $items->reduce(fn ($c, $it) => $c + ((float) $it->price * (int) $it->quantity), 0.0);

        $order = null;

        DB::transaction(function () use (&$order, $items, $total, $userId) {
            $productIds = $items->pluck('product_id')->filter()->unique()->values();
            $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

            $insufficient = [];
            foreach ($items as $it) {
                $p = $products->get($it->product_id);
                if (!$p || !$p->is_active || (int)$p->stock < (int)$it->quantity) {
                    $insufficient[] = $p?->name ?? "Produk #{$it->product_id}";
                }
            }
            if (!empty($insufficient)) {
                throw new \RuntimeException('Kekurangan stok: ' . implode(', ', $insufficient));
            }

            $orderCode = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(4));

            $order = Order::create([
                'user_id'    => $userId,
                'order_code' => $orderCode,
                'total'      => $total,
                'status'     => 'paid', 
            ]);

            foreach ($items as $it) {
                $p = $products->get($it->product_id);

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $it->product_id,
                    'product_name' => $p?->name ?? 'Unknown',
                    'price_each'   => (float) $it->price,
                    'qty'          => (int) $it->quantity,
                    'subtotal'     => (float) $it->price * (int) $it->quantity,
                ]);

                if ($p) {
                    $p->decrement('stock', (int) $it->quantity);
                }
            }

            CartItem::where('user_id', $userId)->delete();
        });

        session(['cart_count' => 0]);

        return redirect()
            ->route('orders.success', ['order' => $order->id])
            ->with('info', 'Order success! Thank you.');
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('orders.success', compact('order'));
    }
}
