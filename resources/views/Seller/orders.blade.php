<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Seller Orders — SweetCrumbs</title>
  <style>
    :root{
      --bg:#FFFAEB;          
      --text:#5B4636;    
      --border:#EADFCC;      
      --muted:#FFF6E7;     
      --primary:#FCC0C5;    
      --primary-600:#CC9299;  
      --shadow:rgba(91,70,54,0.08);
    }

    body{
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;
      margin:0;
      padding:24px;
      color:var(--text);
      background:var(--bg);
    }

    h1{
      color:var(--primary-600);
      margin-top:0;
      margin-bottom:8px;
    }

    table{
      width:100%;
      border-collapse:collapse;
      background:#fff;
      border-radius:12px;
      overflow:hidden;
      box-shadow:0 4px 12px var(--shadow);
    }

    th,td{
      border-bottom:1px solid var(--border);
      padding:10px;
      text-align:left;
    }

    th{
      background:var(--muted);
      color:var(--text);
      font-weight:700;
    }

    tr:nth-child(even){
      background:var(--muted);
    }

    .pill{
      display:inline-block;
      padding:8px 12px;
      border:1px solid var(--border);
      border-radius:999px;
      background:#fff;
      color:var(--text);
      text-decoration:none;
      font-weight:600;
      transition:.2s;
    }

    .pill:hover{
      background:var(--primary);
      color:#fff;
      border-color:var(--primary);
      box-shadow:0 0 0 3px rgba(252,192,197,0.25);
    }

    .muted{
      color:#7A695B;
      opacity:.85;
    }
  </style>
</head>
<body>
  <h1>Orders</h1>
  <a class="pill" href="{{ route('seller.dashboard') }}">← Back to Seller</a>

  <table style="margin-top:14px">
    <thead>
      <tr>
        <th>Order Code</th>
        <th>Buyer</th>
        <th>Product</th>
        <th>Qty</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @forelse($items as $row)
        <tr>
          <td>{{ $row->order->order_code }}</td>
          <td>{{ $row->order->buyer->name ?? 'User #'.$row->order->user_id }}</td>
          <td>{{ $row->product_name }}</td>
          <td>{{ $row->qty }}</td>
          <td>Rp {{ number_format($row->subtotal,0,',','.') }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="6" class="muted" style="text-align:center">
            Belum ada order untuk produkmu.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if(method_exists($items, 'links'))
    <div style="margin-top:12px">{{ $items->links() }}</div>
  @endif
</body>
</html>
