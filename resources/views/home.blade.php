<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>SweetCrumbs Bakery</title>

  <style>
    :root{
      --bg:#FFFAEB; 
      --text:#5B4636; 
      --muted:#FFF6E7; 
      --border:#EADFCC;
      --primary:#FCC0C5; 
      --primary-600:#CC9299; 
      --shadow:rgba(91,70,54,.08);
      --danger:#E9A0A0; 
      --danger-dark:#C26E6E; 
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; background:var(--bg); color:var(--text);
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale;
      line-height:1.55;
    }
    a{text-decoration:none; color:inherit}
    .container{max-width:1080px; margin:0 auto; padding:24px}

    .topbar{
      position:sticky; top:0; z-index:30;
      backdrop-filter:blur(6px);
      background:rgba(255,250,235,.85);
      border-bottom:1px solid var(--border);
    }
    .topbar-inner{max-width:1080px; margin:0 auto; padding:10px 24px; display:flex; align-items:center; gap:12px}
    .brand{display:flex; align-items:center; gap:10px; font-weight:800}
    .dot{width:14px;height:14px;border-radius:4px;background:var(--primary)}
    .spacer{flex:1}
    .iconbtn{display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border:1px solid var(--border);
             border-radius:999px; background:#fff; transition:.15s; font-weight:600; cursor:pointer}
    .iconbtn:hover{border-color:var(--primary); box-shadow:0 0 0 3px rgba(252,192,197,.28)}
    .badge{min-width:18px;height:18px;padding:0 6px;border-radius:999px;background:var(--primary);color:#fff;font-size:12px;display:inline-grid;place-items:center}

    .btn{display:inline-block; padding:10px 16px; border:1px solid var(--border);
         border-radius:999px; background:var(--muted); transition:.15s; font-weight:700; cursor:pointer}
    .btn:hover{border-color:var(--primary); box-shadow:0 0 0 3px rgba(252,192,197,.28)}
    .btn-primary{background:var(--primary); color:#fff; border-color:var(--primary)}
    .btn-primary:hover{background:var(--primary-600)}
    .btn[disabled]{opacity:.45; cursor:not-allowed}

    .hero{position:relative; isolation:isolate; border-radius:20px; overflow:hidden;
          border:1px solid var(--border); box-shadow:0 8px 30px var(--shadow); margin-top:18px}
    .hero img{width:100%; height:360px; object-fit:cover; display:block; filter:brightness(.9)}
    .hero-overlay{position:absolute; inset:0; display:flex; align-items:center; justify-content:center;
      background:linear-gradient(to top, rgba(255,250,235,.85), rgba(255,250,235,.25)); padding:24px}
    .hero-title{font-size:clamp(28px,4vw,44px); font-weight:800; letter-spacing:.4px; text-align:center}
    .hero-sub{font-size:clamp(14px,2vw,18px); opacity:.8; text-align:center; margin-top:6px}

    .grid{display:grid; gap:12px}
    @media(min-width:640px){.grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr));}}
    @media(min-width:940px){.grid-cols-4{grid-template-columns:repeat(4,minmax(0,1fr));}}
    .pill{padding:10px 14px; border:1px solid var(--border); border-radius:999px; background:#fff; text-align:center}
    .card{border:1px solid var(--border); border-radius:16px; background:#fff; padding:18px; box-shadow:0 6px 18px var(--shadow); position:relative}
    .footer{font-size:13px; opacity:.7; text-align:center; margin-top:28px}

    .stars{display:inline-flex; gap:2px; vertical-align:middle}
    .star{font-size:18px}
    .review-item{border-bottom:1px solid var(--border); padding:12px 0}
    .muted{opacity:.75}

    .searchwrap{flex:1; display:flex; gap:8px; align-items:center; max-width:460px}
    .searchwrap input{flex:1; padding:8px 12px; border:1px solid var(--border); border-radius:999px; background:#fff; color:var(--text)}

    .thumb{aspect-ratio: 1.4/1; border-radius:12px; overflow:hidden; border:1px solid var(--border);
           background:var(--muted); display:grid; place-items:center; margin:-4px -4px 10px}
    .soldout-badge{
      position:absolute; top:14px; right:14px; background:var(--danger-dark); color:#fff; font-weight:800;
      padding:6px 10px; border-radius:999px; font-size:12px
    }
  </style>
</head>
<body>

  <div class="topbar">
    <div class="topbar-inner">
      <div class="brand">
        <span class="dot" aria-hidden="true"></span>
        <span>SweetCrumbs</span>
      </div>

      <form class="searchwrap" action="{{ route('home') }}" method="GET">
        <input type="text" name="q" value="{{ $q ?? request('q') }}" placeholder="Search all products…" />
        <button class="iconbtn" type="submit">Search</button>
      </form>

      <div class="spacer"></div>

      @php $cartCount = session('cart_count', 0); @endphp
      <a class="iconbtn" href="{{ route('cart') }}">
        <span>🛒 Cart</span>
        <span class="badge">{{ $cartCount }}</span>
      </a>

      @if(session('is_seller'))
        <a class="iconbtn" href="{{ route('seller.dashboard') }}">🏪 Seller</a>
      @endif

      @auth
        <a class="iconbtn" href="{{ route('profile.show') }}">
          👤 {{ \Illuminate\Support\Str::limit(auth()->user()->name ?? 'User', 14) }}
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline">
          @csrf
          <button class="iconbtn" type="submit" style="margin-left:8px">🚪 Logout</button>
        </form>
      @else
        <a class="iconbtn" href="{{ route('login') }}">👤 Login / Register</a>
      @endauth
    </div>
  </div>

  <div class="container">
    @if(session('info'))
      <div class="card" style="background:var(--muted)">{{ session('info') }}</div>
    @endif

    @if(($q ?? '') !== '')
      <div class="card" style="margin-top:12px;background:#fff">Showing results for “{{ $q }}”.</div>
    @endif

    @if(($q ?? '') === '')
      <section class="hero">
        <img src="{{ asset('https://i.imgur.com/gn9DKIj.png') }}" alt="Fresh bakery assortment">
        <div class="hero-overlay">
          <div>
            <div class="hero-title">SweetCrumbs Bakery</div>
            <div class="hero-sub">Freshly baked happiness — every single day.</div>
            <div style="text-align:center; margin-top:16px">
              <a href="#categories" class="btn btn-primary">Explore Menu</a>
            </div>
          </div>
        </div>
      </section>

      <section id="categories" style="margin-top:28px">
        <h2 style="font-size:22px; margin:0 0 12px 0">Browse by Category</h2>
        <div class="grid grid-cols-2 grid-cols-4">
          <a class="pill" href="{{ url('/category/pastry') }}">Pastry</a>
          <a class="pill" href="{{ url('/category/cake') }}">Cake</a>
          <a class="pill" href="{{ url('/category/bread') }}">Bread</a>
          <a class="pill" href="{{ url('/category/dessert_box') }}">Dessert Box</a>
        </div>
      </section>
    @endif

    @isset($products)
      <section style="margin-top:28px">
        <h2 style="font-size:22px; margin-bottom:12px">
          {{ ($q ?? '') !== '' ? 'Products' : 'New Arrivals' }}
        </h2>

        @if($products->count() === 0)
          <div class="card">No products found{{ ($q ?? '') !== '' ? ' for "'.e($q).'"' : '' }}.</div>
        @else
          <div class="grid grid-cols-2 grid-cols-4">
            @foreach($products as $p)
              <div class="card">
                {{-- badge sold out di pojok jika stok 0 --}}
                @if((int)$p->stock <= 0)
                  <div class="soldout-badge">Sold out</div>
                @endif

                {{-- gambar produk --}}
                <div class="thumb">
                  @if(!empty($p->image_url))
                    <img src="{{ $p->image_url }}" alt="{{ $p->name }}"
                         style="width:100%;height:100%;object-fit:cover;display:block"
                         onerror="this.style.display='none'">
                  @else
                    <div class="muted" style="padding:20px">No image</div>
                  @endif
                </div>

                <div style="font-weight:700">{{ $p->name }}</div>
                <div class="muted" style="margin:6px 0">{{ ucfirst(str_replace('_',' ',$p->category)) }}</div>
                <div style="font-weight:700">Rp {{ number_format((float)$p->price, 0, ',', '.') }}</div>

                {{-- stok --}}
                @if((int)$p->stock > 0)
                  <div class="muted" style="margin-top:6px">
                    Stock: {{ (int)$p->stock }}
                    @if((int)$p->stock <= 5)
                      • only {{ (int)$p->stock }} left
                    @endif
                  </div>
                @else
                  <div style="margin-top:6px; color:var(--danger-dark); font-weight:700">Sold out</div>
                @endif

                {{-- tombol add to cart: disable jika stok habis --}}
                @if((int)$p->stock > 0)
                  <form action="{{ route('cart.add') }}" method="POST" style="margin-top:10px">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $p->id }}">
                    <input type="hidden" name="qty" value="1">
                    <button class="btn" type="submit">Add to Cart</button>
                  </form>
                @else
                  <button class="btn" type="button" disabled style="margin-top:10px">Add to Cart</button>
                @endif
              </div>
            @endforeach
          </div>

          <div style="margin-top:12px">
            {{ $products->links() }}
          </div>
        @endif
      </section>
    @endisset

    @if(($q ?? '') === '')
      <section style="margin-top:28px">
        <div class="card">
          <div style="display:flex; gap:16px; align-items:center; flex-wrap:wrap">
            <div>
              <div style="font-size:20px; font-weight:800">Store Rating</div>
              <div class="stars" aria-label="Average rating">
                @php $rounded = isset($avgRating) ? round($avgRating) : 0; @endphp
                @for($i=1; $i<=5; $i++)
                  <span class="star">{{ $i <= $rounded ? '★' : '☆' }}</span>
                @endfor
                <span style="margin-left:8px; font-weight:700">{{ number_format((float)($avgRating ?? 0), 2) }}/5</span>
                <span class="muted" style="margin-left:6px">({{ (int)($totalReviews ?? 0) }} reviews)</span>
              </div>
            </div>

            @auth
              <form action="{{ route('reviews.store') }}" method="POST" style="margin-left:auto; display:flex; gap:8px; align-items:center; flex-wrap:wrap">
                @csrf
                <label for="rating">Your Rating:</label>
                <select name="rating" id="rating" required style="padding:8px;border:1px solid var(--border);border-radius:10px">
                  <option value="" disabled selected>Choose</option>
                  <option value="5">★★★★★ (5)</option>
                  <option value="4">★★★★☆ (4)</option>
                  <option value="3">★★★☆☆ (3)</option>
                  <option value="2">★★☆☆☆ (2)</option>
                  <option value="1">★☆☆☆☆ (1)</option>
                </select>
                <input type="text" name="comment" placeholder="Write a short review (optional)" maxlength="600" style="padding:8px;border:1px solid var(--border);border-radius:10px;min-width:240px">
                <button class="btn btn-primary" type="submit">Submit Review</button>
              </form>
            @else
              <div style="margin-left:auto" class="muted">
                <a href="{{ route('login') }}" class="btn">Login to write a review</a>
              </div>
            @endauth
          </div>

          <div style="margin-top:16px">
            @forelse($reviews ?? [] as $r)
              <div class="review-item">
                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap">
                  <div style="font-weight:700">{{ \Illuminate\Support\Str::limit(optional($r->user)->name ?? 'User', 24) }}</div>
                  <div class="stars" aria-label="User rating">
                    @for($i=1; $i<=5; $i++)
                      <span class="star">{{ $i <= (int)$r->rating ? '★' : '☆' }}</span>
                    @endfor
                  </div>
                  <div class="muted">· {{ $r->created_at->diffForHumans() }}</div>
                </div>
                @if($r->comment)
                  <div style="margin-top:6px">{{ $r->comment }}</div>
                @endif
              </div>
            @empty
              <div class="muted">No reviews yet. Be the first!</div>
            @endforelse
          </div>
        </div>
      </section>
    @endif

    <div class="footer">© {{ date('Y') }} SweetCrumbs Bakery</div>
  </div>
</body>
</html>
