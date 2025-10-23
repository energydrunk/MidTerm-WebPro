<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Profile – SweetCrumbs</title>
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
    body{
      margin:0;
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      color:var(--text);
      background:var(--bg);
      line-height:1.55;
    }

    .container{max-width:1080px;margin:0 auto;padding:24px}

    .topbar{
      padding:14px 24px;
      border-bottom:1px solid var(--border);
      display:flex;gap:12px;align-items:center;
      background:var(--muted);
    }

    .spacer{flex:1}

    .iconbtn{
      display:inline-flex;gap:8px;align-items:center;
      padding:8px 12px;
      border:1px solid var(--border);
      border-radius:999px;
      background:#fff;
      cursor:pointer;
      font-weight:600;
      transition:.2s;
    }

    .iconbtn:hover{
      border-color:var(--primary);
      box-shadow:0 0 0 3px rgba(252,192,197,.3);
    }

    .card{
      border:1px solid var(--border);
      border-radius:16px;
      background:#fff;
      padding:18px;
      margin-bottom:16px;
    }

    table{width:100%;border-collapse:collapse}
    th,td{
      padding:10px;
      border-bottom:1px solid var(--border);
      text-align:left;
    }

    .badge{
      display:inline-block;
      padding:2px 8px;
      border:1px solid var(--border);
      border-radius:999px;
      background:var(--muted);
      font-size:12px;
    }

    .btn{
      padding:8px 14px;
      border-radius:999px;
      border:1px solid var(--primary);
      background:var(--primary);
      color:#fff;
      font-weight:600;
      cursor:pointer;
      transition:.2s;
    }

    .btn:hover{background:var(--primary-600)}
  </style>
</head>
<body>
  <div class="topbar">
    <a class="iconbtn" href="{{ route('home') }}">← Home</a>
    <div class="spacer"></div>
    <a class="iconbtn" href="{{ route('cart') }}">🛒 Cart</a>
    <form action="{{ route('logout') }}" method="POST" style="display:inline">
      @csrf <button class="iconbtn" type="submit">🚪 Logout</button>
    </form>
  </div>

  <div class="container">
    <h1 style="margin:0 0 16px 0;color:var(--primary-600)">Your Profile</h1>

    <div class="card">
      <div style="font-weight:700;font-size:18px">{{ $user->name }}</div>
      <div style="margin-top:6px">{{ $user->email }}</div>
      <div class="badge" style="margin-top:10px">
        Registered: {{ $user->created_at?->format('d M Y H:i') }}
      </div>
    </div>

    <div class="card">
      <h2 style="margin:0 0 12px 0;font-size:18px;color:var(--primary-600)">Purchase History</h2>

      @if($orders->count() === 0)
        <div class="muted">No orders yet.</div>
      @else
        <table>
          <thead>
            <tr>
              <th>Order Code</th>
              <th>Date</th>
              <th>Status</th>
              <th style="text-align:right">Total</th>
            </tr>
          </thead>
          <tbody>
          @foreach($orders as $o)
            <tr>
              <td>{{ $o->order_code }}</td>
              <td>{{ $o->created_at?->format('d M Y H:i') }}</td>
              <td><span class="badge">{{ $o->status }}</span></td>
              <td style="text-align:right">Rp {{ number_format((float)$o->total,0,',','.') }}</td>
            </tr>
            <tr>
              <td colspan="4" style="background:var(--muted)">
                @foreach($o->items as $it)
                  @php
                    $name = optional($it->product)->name ?? 'Item';
                    $qty  = (int)$it->qty;
                    $price = (float)$it->price_each;
                  @endphp
                  <div style="padding:6px 0">
                    • {{ $name }} × {{ $qty }}
                    — Rp {{ number_format($price * $qty, 0, ',', '.') }}
                  </div>
                @endforeach
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>

        <div style="margin-top:12px">
          {{ $orders->links() }}
        </div>
      @endif
    </div>
  </div>
</body>
</html>
