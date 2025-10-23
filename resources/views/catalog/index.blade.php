<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $title }} – SweetCrumbs Bakery</title>

  <style>
    :root{
      --bg:#FFFAEB;             
      --text:#5B4636;           
      --muted:#FFF6E7;        
      --border:#EADFCC;       
      --primary:#FCC0C5;      
      --primary-600:#CC9299;    
      --shadow:rgba(91,70,54,0.08); 
      --danger:#E9A0A0;     
      --danger-dark:#C26E6E;  
      --highlight:#FEEDAA;     
    }

    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;
      background:var(--bg);
      color:var(--text);
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      line-height:1.55;
    }

    a{text-decoration:none; color:inherit}
    .container{max-width:1080px; margin:0 auto; padding:24px}

    .btn{
      display:inline-block; padding:8px 14px;
      border:1px solid var(--primary);
      border-radius:999px;
      background:var(--primary);
      color:#fff; font-weight:600; cursor:pointer;
      transition:.2s;
    }
    .btn:hover{background:var(--primary-600)}
    .btn:disabled{opacity:.5; cursor:not-allowed; filter:grayscale(35%)}

    .card{
      border:1px solid var(--border);
      border-radius:16px;
      background:#fff;
      padding:18px;
      box-shadow:0 6px 18px var(--shadow);
      margin-bottom:16px;
      position:relative;
    }

    .grid{display:grid; gap:16px}
    @media(min-width:640px){.grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr));}}
    @media(min-width:940px){.grid-cols-4{grid-template-columns:repeat(4,minmax(0,1fr));}}

    .topbar{
      padding:14px 24px;
      border-bottom:1px solid var(--border);
      display:flex; align-items:center; gap:14px; flex-wrap:wrap;
      background:var(--muted);
    }
    .spacer{flex:1}
    .iconbtn{
      display:inline-flex; align-items:center; gap:8px;
      padding:8px 12px;
      border:1px solid var(--border);
      border-radius:999px;
      background:#fff;
      transition:.15s;
      font-weight:600;
      cursor:pointer;
    }
    .iconbtn:hover{
      border-color:var(--primary);
      box-shadow:0 0 0 3px rgba(252,192,197,0.3);
    }

    .searchwrap{display:flex; gap:8px; align-items:center; max-width:420px}
    .searchwrap input{
      flex:1; padding:8px 12px;
      border:1px solid var(--border);
      border-radius:999px;
      background:#fff;
    }

    .muted{opacity:.75}
    .danger{color:var(--danger)}
    .stock{font-size:13px}

    .thumb{
      aspect-ratio:1.4/1;
      border-radius:12px;
      overflow:hidden;
      border:1px solid var(--border);
      background:var(--muted);
      display:grid;
      place-items:center;
      margin:-4px -4px 10px;
    }
    .soldout-badge{
      position:absolute; top:14px; right:14px;
      background:var(--danger-dark);
      color:#fff;
      font-weight:800;
      padding:6px 10px;
      border-radius:999px;
      font-size:12px;
      box-shadow:0 2px 4px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

  <div class="topbar">
    <a href="{{ route('home') }}" class="iconbtn">← Home</a>

    {{-- Search khusus kategori ini --}}
    <form class="searchwrap" action="{{ route('catalog.category', ['category' => $category]) }}" method="GET">
      <input type="text" name="q" value="{{ $q ?? request('q') }}" placeholder="Search in {{ $title }}…" />
      <button class="iconbtn" type="submit">Search</button>
    </form>

    <div class="spacer"></div>

    @if(session('is_seller'))
      <a href="{{ route('seller.dashboard') }}" class="iconbtn">🏪 Seller</a>
    @endif

    <a href="{{ route('cart') }}" class="iconbtn">🛒 Cart</a>
  </div>

  <div class="container">
    <h1 style="margin-bottom:8px">{{ $title }}</h1>

    @if(($q ?? '') !== '')
      <div class="card" style="margin-top:6px;background:var(--muted)">Showing results for “{{ $q }}” in {{ $title }}.</div>
    @endif

    @if(session('info'))
      <div class="card" style="background:var(--highlight)">{{ session('info') }}</div>
    @endif
    @if($errors->any())
      <div class="card" style="background:#FFF1F2; border-color:#FECACA">
        <ul style="margin:0; padding-left:18px">
          @foreach($errors->all() as $e)
            <li class="danger">{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="grid grid-cols-2 grid-cols-4">
      @forelse($products as $p)
        @php $stock = (int)($p->stock ?? 0); @endphp
        <div class="card">
          {{-- badge sold out --}}
          @if($stock <= 0)
            <div class="soldout-badge">Sold out</div>
          @endif

          <div class="thumb">
            @if(!empty($p->image_url))
              <img src="{{ $p->image_url }}" alt="{{ $p->name }}"
                   style="width:100%;height:100%;object-fit:cover;display:block"
                   onerror="this.style.display='none'">
            @else
              <div class="muted" style="padding:20px">No image</div>
            @endif
          </div>

          <h3 style="margin:0 0 6px 0">{{ $p->name }}</h3>
          <p class="muted" style="margin:0 0 6px 0">{{ ucfirst(str_replace('_',' ',$p->category)) }}</p>
          <p style="margin:0; font-weight:700">Rp {{ number_format($p->price, 0, ',', '.') }}</p>

          <p class="stock" style="margin:6px 0 0 0">
            @if($stock > 0)
              Stock: {{ $stock }}
              @if($stock <= 3)
                <span class="danger">· only {{ $stock }} left</span>
              @endif
            @else
              <span class="danger">Out of stock</span>
            @endif
          </p>

          <form action="{{ route('cart.add') }}" method="POST" style="margin-top:10px">
            @csrf
            <input type="hidden" name="product_id" value="{{ $p->id }}">
            <input type="hidden" name="qty" value="1">
            <button class="btn" type="submit" {{ $stock < 1 ? 'disabled' : '' }}>
              {{ $stock < 1 ? 'Unavailable' : 'Add to cart' }}
            </button>
          </form>
        </div>
      @empty
        <div class="card">No products found{{ ($q ?? '') !== '' ? ' for "' . e($q) . '"' : '' }} in {{ $title }}.</div>
      @endforelse
    </div>

    <div style="margin-top:20px">
      {{ $products->links() }}
    </div>
  </div>

</body>
</html>
