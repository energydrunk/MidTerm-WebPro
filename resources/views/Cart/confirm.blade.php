<!-- resources/views/cart/confirm.blade.php -->
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Confirm Order — SweetCrumbs</title>
  <style>
    :root{
      --bg:#FFFAEB;          
      --text:#5B4636;       
      --border:#EADFCC;     
      --primary:#FCC0C5;     
      --primary-600:#CC9299; 
    }
    body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;color:var(--text);background:var(--bg)}
    .wrap{max-width:900px;margin:32px auto;padding:0 20px}
    .card{border:1px solid var(--border);border-radius:14px;padding:18px;background:#fff}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid var(--border);text-align:left}
    .row{display:flex;gap:12px;flex-wrap:wrap;margin-top:14px}
    .btn{padding:10px 16px;border-radius:999px;border:1px solid var(--primary);background:#fff;color:var(--primary);font-weight:600;cursor:pointer;transition:.2s}
    .btn-primary{background:var(--primary);color:#fff}
    .btn:hover{border-color:var(--primary-600);color:var(--primary-600)}
    .btn-primary:hover{background:var(--primary-600);border-color:var(--primary-600)}
    a.btn{display:inline-block;text-decoration:none}
    h1{color:var(--primary-600)}
    select{padding:10px 12px;border:1px solid var(--border);border-radius:10px;background:#fff}
  </style>
</head>
<body>
  <div class="wrap">
    <h1 style="margin:0 0 14px">Confirm Your Order</h1>

    <div class="card">
      @if($items->isEmpty())
        <p>Cart is empty.</p>
        <a class="btn" href="{{ route('home') }}">← Back to Home</a>
      @else
        <table>
          <thead>
            <tr>
              <th>Product</th>
              <th style="width:110px">Price</th>
              <th style="width:90px">Qty</th>
              <th style="width:140px">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $it)
              <tr>
                <td>{{ optional($it->product)->name ?? 'Unknown' }}</td>
                <td>Rp {{ number_format($it->price,0,',','.') }}</td>
                <td>{{ $it->quantity }}</td>
                <td>Rp {{ number_format($it->price * $it->quantity,0,',','.') }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" style="text-align:right">Total</th>
              <th>Rp {{ number_format($total,0,',','.') }}</th>
            </tr>
          </tfoot>
        </table>

        <form action="{{ route('cart.checkout') }}" method="POST" class="row">
          @csrf
          <select name="payment_method">
            <option value="dummy" selected>Dummy Payment</option>
            <option value="bank_transfer">Bank Transfer</option>
            <option value="ewallet">E-Wallet</option>
          </select>

          <a class="btn" href="{{ route('cart') }}">← Back to Cart</a>
          <button class="btn btn-primary" type="submit">Pay Now</button>
        </form>
      @endif
    </div>
  </div>
</body>
</html>
