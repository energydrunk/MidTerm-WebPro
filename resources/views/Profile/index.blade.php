<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Your Profile — SweetCrumbs</title>
  <style>
    :root{
      --bg:#FFFAEB;             
      --text:#5B4636;         
      --muted:#FFF6E7;         
      --border:#EADFCC;       
      --primary:#FCC0C5;    
      --primary-600:#CC9299;   
      --shadow:rgba(91,70,54,.08); 
    }

    *{box-sizing:border-box}
    body{
      margin:0; background:var(--bg); color:var(--text);
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif; line-height:1.55
    }
    a{text-decoration:none;color:inherit}
    .container{max-width:1080px;margin:0 auto;padding:24px}

    .topbar{
      position:sticky;top:0;z-index:30;
      backdrop-filter:blur(6px);
      background:rgba(255,250,235,.85);
      border-bottom:1px solid var(--border);
    }
    .topbar-inner{
      max-width:1080px;margin:0 auto;padding:10px 24px;
      display:flex;align-items:center;gap:12px;
    }
    .iconbtn{
      display:inline-flex;align-items:center;gap:8px;
      padding:8px 12px;border:1px solid var(--border);
      border-radius:999px;background:#fff;font-weight:600;
      transition:.15s;
    }
    .iconbtn:hover{
      border-color:var(--primary);
      box-shadow:0 0 0 3px rgba(252,192,197,.3);
    }

    .spacer{flex:1}

    .card{
      border:1px solid var(--border);
      border-radius:16px;
      background:#fff;
      padding:18px;
      box-shadow:0 6px 18px var(--shadow);
      margin-bottom:16px;
    }

    .badge{
      min-width:18px;height:18px;padding:0 6px;
      border-radius:999px;
      background:var(--primary);
      color:#fff;font-size:12px;
      display:inline-grid;place-items:center;
    }

    table{width:100%;border-collapse:collapse}
    th,td{
      padding:10px;
      border-bottom:1px solid var(--border);
      text-align:left;vertical-align:top;
    }

    .muted{opacity:.75}
    .pill{
      padding:8px 12px;border:1px solid var(--border);
      border-radius:999px;background:#fff;display:inline-block;
      color:var(--text);
      transition:.2s;
    }
    .pill:hover{
      border-color:var(--primary);
      color:var(--primary-600);
    }
  </style>
</head>
<body>

  <div class="topbar">
    <div class="topbar-inner">
      <a class="iconbtn" href="{{ route('home') }}">← Home</a>
      <div class="spacer"></div>

      @php $cartCount = session('cart_count', 0); @endphp
      <a class="iconbtn" href="{{ route('cart') }}">
        🛒 Cart <span class="badge" style="margin-left:6px">{{ $cartCount }}</span>
      </a>

      @if(session('is_seller'))
        <a class="iconbtn" href="{{ route('seller.dashboard') }}">🏪 Seller</a>
      @endif

      <form action="{{ route('logout') }}" method="POST" style="display:inline;margin-left:8px">
        @csrf
        <button class="iconbtn" type="submit">🚪 Logout</button>
      </form>
    </div>
  </div>

  <div class="container">
    @if(session('info'))
      <div class="card" style="background:var(--muted)">{{ session('info') }}</div>
    @endif

    <div class="card">
      <h2 style="margin:0 0 6px 0">Your Profile</h2>
      <div class="muted" style="margin-bottom:10px">Account overview</div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
        <div>
          <div class="muted">Name</div>
          <div style="font-weight:700">{{ $user->name }}</div>
        </div>
        <div>
          <div class="muted">Email</div>
          <div style="font-weight:700">{{ $user->email }}</div>
        </div>
        <div>
          <div class="muted">Registered</div>
          <div style="font-weight:700">{{ optional($user->created_at)->format('d M Y, H:i') }}</div>
        </div>
        <div>
          <div class="muted">Total Orders</div>
          <div style="font-weight:700">{{ $orders->total() }}</div>
        </div>
      </div>
    </div>

    <div class="card">
      <h2 style="margin:0 0 6px 0">Purchase History</h2>
      <div class="muted" style="margin-bottom:10px">Your last {{ $orders->count() }} orders</div>

      @if($orders->count() === 0)
        <div class="muted">You haven’t placed any orders yet.</div>
      @else
        @foreach($orders as $order)
          <div class="card" style="margin:0 0 12px 0; border-style:dashed">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
              <span class="pill">Order ID: #{{ $order->id }}</span>
              <span class="pill">Code: {{ $order->order_code }}</span>
              <span class="pill">Date: {{ $order->created_at->format('d M Y, H:i') }}</span>
              <span class="pill">Status: {{ ucfirst($order->status) }}</span>
              <span class="pill">Total: Rp {{ number_format((float)$order->total,0,',','.') }}</span>
            </div>

            <div style="margin-top:10px">
              <table>
                <thead>
                  <tr>
                    <th style="width:45%">Item</th>
                    <th style="width:15%">Qty</th>
                    <th style="width:20%">Price</th>
                    <th style="width:20%">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->items as $it)
                    @php
                      $name = optional($it->product)->name ?? ($it->product_name ?? 'Unknown');
                    @endphp
                    <tr>
                      <td>{{ $name }}</td>
                      <td>{{ (int)$it->qty }}</td>
                      <td>Rp {{ number_format((float)$it->price_each,0,',','.') }}</td>
                      <td>Rp {{ number_format((float)$it->subtotal,0,',','.') }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @endforeach

        <div style="margin-top:12px">
          {{ $orders->links() }}
        </div>
      @endif
    </div>

    <div style="text-align:center;opacity:.7;margin-top:24px">
      © {{ date('Y') }} SweetCrumbs Bakery
    </div>
  </div>
</body>
</html>
