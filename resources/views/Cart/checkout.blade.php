<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout — SweetCrumbs</title>
  <style>
    :root{
      --bg:#FFFAEB;           
      --surface:#FFFFFF;      
      --text:#5B4636;        
      --text-muted:#7A695B;   
      --border:#EADFCC;       
      --primary:#FCC0C5;    
      --primary-600:#CC9299;  
      --muted:#E7BFA7;        
      --highlight:#FEEDAA;   
    }

    body{
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;
      margin:0;
      background:var(--bg);
      color:var(--text);
    }

    .container{
      max-width:900px;
      margin:0 auto;
      padding:24px;
    }

    .card{
      border:1px solid var(--border);
      border-radius:16px;
      background:var(--surface);
      padding:18px;
      margin-bottom:18px;
      box-shadow:0 1px 2px rgba(0,0,0,0.05);
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

    h1,h3{
      color:var(--primary-600);
    }

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

    .muted{
      opacity:.9;
      color:var(--text-muted);
    }

    a.muted:hover{
      color:var(--primary-600);
    }

    input[type="checkbox"]{
      accent-color:var(--primary);
    }

    tr:last-child td{
      background:var(--highlight);
      color:var(--text);
    }
  </style>
</head>
<body>
<div class="container">
  <h1 style="margin:0 0 12px 0">Checkout</h1>
  <a href="{{ route('cart') }}" class="muted">← Back to cart</a>

  <div class="card">
    <h3 style="margin-top:0">Order Summary</h3>
    <table>
      <thead>
        <tr><th>Item</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr>
      </thead>
      <tbody>
      @foreach($items as $it)
        @php
          $name  = optional($it->product)->name ?? 'Unknown';
          $qty   = (int) $it->quantity;
          $price = (float) $it->price;
          $sub   = $price * $qty;
        @endphp
        <tr>
          <td>{{ $name }}</td>
          <td>{{ $qty }}</td>
          <td>Rp {{ number_format($price,0,',','.') }}</td>
          <td>Rp {{ number_format($sub,0,',','.') }}</td>
        </tr>
      @endforeach
      <tr>
        <td colspan="3" style="text-align:right;font-weight:700">Total</td>
        <td style="font-weight:700">Rp {{ number_format($total,0,',','.') }}</td>
      </tr>
      </tbody>
    </table>
  </div>

  <form action="{{ route('cart.checkout') }}" method="POST" class="card">
    @csrf
    <label style="display:block;margin-top:4px">
      <input type="checkbox" name="confirm" value="1" required>
      I confirm this order.
    </label>

    <div style="margin-top:12px">
      <button class="btn" type="submit">Order Now</button>
    </div>
  </form>
</div>
</body>
</html>
