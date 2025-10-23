<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order Success — SweetCrumbs</title>
  <style>
    :root{
      --bg:#FFFAEB;          
      --text:#5B4636;        
      --border:#EADFCC;   
      --primary:#FCC0C5;      
      --primary-600:#CC9299;   
    }

    body{
      font-family:system-ui;
      margin:0; padding:24px;
      color:var(--text);
      background:var(--bg);
    }

    h1{ color:var(--primary-600) }

    .card{
      border:1px solid var(--border);
      border-radius:16px;
      padding:18px;
      margin-bottom:18px;
      background:#fff;
    }

    .pill{
      display:inline-block;
      padding:8px 12px;
      border:1px solid var(--border);
      border-radius:999px;
      text-decoration:none;
      color:var(--primary-600);
      background:#fff;
      transition:.2s;
    }
    .pill:hover{
      border-color:var(--primary);
      color:#fff;
      background:var(--primary);
    }

    table{ width:100%; border-collapse:collapse; margin:12px 0; background:#fff }
    th,td{ border-bottom:1px solid var(--border); padding:8px; text-align:left }
  </style>
</head>
<body>
  <h1>Thank you! </h1>

  @if(session('info')) <p>{{ session('info') }}</p> @endif

  <div class="card">
    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>Order Code:</strong> {{ $order->order_code }}</p>
    <p><strong>Total:</strong> Rp {{ number_format($order->total,0,',','.') }}</p>
  </div>

  <div class="card">
    <h3>Items</h3>
    <table>
      <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
      <tbody>
        @foreach($order->items as $it)
          <tr>
            <td>{{ $it->product_name }}</td>
            <td>Rp {{ number_format($it->price_each,0,',','.') }}</td>
            <td>{{ $it->qty }}</td>
            <td>Rp {{ number_format($it->subtotal,0,',','.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <a class="pill" href="{{ route('home') }}">← Back to Home</a>
</body>
</html>
