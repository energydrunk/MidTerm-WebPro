<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Seller — {{ $title }}</title>
  <style>
    :root{
      --bg:#FFFAEB;           
      --text:#5B4636;     
      --muted:#FFF6E7;      
      --border:#EADFCC;       
      --primary:#FCC0C5;     
      --primary-600:#CC9299;  
    }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      background:var(--bg);
      color:var(--text);
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;
      line-height:1.55;
    }

    a{text-decoration:none;color:inherit}
    .container{max-width:1080px;margin:0 auto;padding:24px}

    table{
      width:100%;
      border-collapse:collapse;
      background:#fff;
      border-radius:12px;
      overflow:hidden;
      box-shadow:0 4px 10px rgba(91,70,54,0.08);
    }

    th,td{
      padding:10px;
      border-bottom:1px solid var(--border);
      text-align:left;
    }

    th{
      background:var(--muted);
      font-weight:700;
      color:var(--text);
    }

    .top{
      display:flex;
      align-items:center;
      gap:10px;
      margin-bottom:16px;
      flex-wrap:wrap;
    }

    .btn{
      padding:8px 12px;
      border-radius:10px;
      border:1px solid var(--border);
      background:#fff;
      color:var(--text);
      font-weight:600;
      transition:.2s;
      cursor:pointer;
    }

    .btn:hover{
      border-color:var(--primary);
      background:var(--primary);
      color:#fff;
      box-shadow:0 2px 6px rgba(252,192,197,0.35);
    }

    h1{
      margin:0;
      color:var(--primary-600);
    }

    tr:nth-child(even){
      background:var(--muted);
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="top">
      <a href="{{ route('seller.dashboard') }}" style="color:var(--primary-600)">← Seller Dashboard</a>
      <h1>{{ $title }}</h1>
      <div style="margin-left:auto">
        <a class="btn" href="{{ route('catalog.category',$category) }}">View as Customer</a>
      </div>
    </div>

    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $i => $p)
          <tr>
            <td>{{ $products->firstItem() + $i }}</td>
            <td>{{ $p->name }}</td>
            <td>Rp {{ number_format($p->price,0,',','.') }}</td>
            <td>{{ $p->stock ?? 0 }}</td>
            <td>{{ $p->is_active ? 'Active' : 'Hidden' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align:center;color:#7A695B;">No products in this category yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    <div style="margin-top:16px">{{ $products->links() }}</div>
  </div>
</body>
</html>
