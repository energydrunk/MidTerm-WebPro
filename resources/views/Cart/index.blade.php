<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Cart — SweetCrumbs</title>
  <style>
    :root{
      --bg:#FFFAEB;             
      --text:#5B4636;          
      --border:#EADFCC;        
      --primary:#FCC0C5;     
      --primary-600:#CC9299;    
      --muted:#E7BFA7;          
      --danger:#E9A0A0;        
      --danger-dark:#C26E6E;   
    }

    body{
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;
      margin:0;
      background:var(--bg);
      color:var(--text);
    }

    .container{
      max-width:980px;
      margin:0 auto;
      padding:24px;
    }

    .card{
      border:1px solid var(--border);
      border-radius:16px;
      background:#fff;
      padding:18px;
      margin-bottom:18px;
      box-shadow:0 2px 6px rgba(2,6,23,.06);
    }

    table{
      width:100%;
      border-collapse:collapse;
    }

    th,td{
      padding:10px;
      border-bottom:1px solid var(--border);
      text-align:left;
    }

    .right{text-align:right}
    .muted{opacity:.85;color:var(--muted)}

    .btn{
      padding:10px 16px;
      border-radius:999px;
      border:1px solid var(--primary);
      background:var(--primary);
      color:#fff;
      font-weight:700;
      cursor:pointer;
      transition:.2s;
    }

    .btn:hover{
      background:var(--primary-600);
      border-color:var(--primary-600);
    }

    .btn-outline{
      padding:8px 12px;
      border-radius:999px;
      border:1px solid var(--border);
      background:#fff;
      color:var(--text);
      cursor:pointer;
      transition:.2s;
    }

    .btn-outline:hover{
      border-color:var(--primary);
      color:var(--primary-600);
    }

    .btn-danger{
      padding:8px 12px;
      border-radius:999px;
      border:1px solid var(--danger);
      background:var(--danger);
      color:#fff;
      cursor:pointer;
      transition:.2s;
    }

    .btn-danger:hover{
      background:var(--danger-dark);
      border-color:var(--danger-dark);
    }

    input[type=number]{
      width:80px;
      padding:6px 8px;
      border:1px solid var(--border);
      border-radius:10px;
      background:#fff;
      color:var(--text);
    }

    .row-actions{display:flex;gap:8px;align-items:center}
    .actions-bar{display:flex;justify-content:space-between;gap:12px;margin-top:12px}
    h1{color:var(--primary-600)}
  </style>
</head>
<body>
<div class="container">
  <h1 style="margin:0 0 12px 0">Your Cart</h1>

  @if(session('info'))
    <div class="card" style="background:var(--bg);border-color:var(--border)">{{ session('info') }}</div>
  @endif

  @if($items->isEmpty())
    <p>Cart is empty.</p>
    <a class="btn-outline" href="{{ route('home') }}">← Back to Home</a>
  @else
    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Item</th>
            <th class="right">Price</th>
            <th class="right">Qty</th>
            <th class="right">Subtotal</th>
            <th class="right">Action</th>
          </tr>
        </thead>
        <tbody>
        @foreach($items as $it)
          @php
            $product = $it->product;
            $name    = $product?->name ?? 'Unknown';
            $price   = (float) $it->price;
            $qty     = (int) $it->quantity;
            $sub     = $price * $qty;
          @endphp
          <tr>
            <td>
              <div style="font-weight:600">{{ $name }}</div>
              <div class="muted" style="font-size:12px">#{{ $product->id ?? $it->product_id }}</div>
            </td>

            <td class="right">Rp {{ number_format($price,0,',','.') }}</td>

            <td class="right">
              <form action="{{ route('cart.update', $it->product_id) }}" method="POST" class="row-actions">
                @csrf
                <input type="number" name="qty" min="1" value="{{ $qty }}">
                <button class="btn-outline" type="submit">Update</button>
              </form>
            </td>

            <td class="right">Rp {{ number_format($sub,0,',','.') }}</td>

            <td class="right">
              <form action="{{ route('cart.remove', $it->product_id) }}" method="POST" onsubmit="return confirm('Remove this item?')">
                @csrf
                @method('DELETE')
                <button class="btn-danger" type="submit">Remove</button>
              </form>
            </td>
          </tr>
        @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" class="right">Total</th>
            <th class="right">Rp {{ number_format($total,0,',','.') }}</th>
            <th></th>
          </tr>
        </tfoot>
      </table>

      <div class="actions-bar">
        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Clear entire cart?')">
          @csrf
          <button type="submit" class="btn-outline">Clear Cart</button>
        </form>

        <div style="display:flex;gap:8px">
          <a href="{{ route('home') }}" class="btn-outline">← Continue Shopping</a>
          <a href="{{ route('cart.confirm') }}" class="btn">Proceed to Checkout</a>
        </div>
      </div>
    </div>
  @endif
</div>
</body>
</html>
