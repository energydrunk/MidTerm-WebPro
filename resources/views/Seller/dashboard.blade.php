<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Seller Dashboard — SweetCrumbs</title>
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
      --danger-600:#C26E6E; 
      --ok-bg:#E9F7EF; --ok-bd:#BFE7CF; --ok-tx:#0B6B2F;
      --err-bg:#FDECEC; --err-bd:#F3C2C2; --err-tx:#8A1F1F;
      --muted-text:#7A695B;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0;background:var(--bg);color:var(--text);
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial
    }
    a{text-decoration:none;color:inherit}
    .container{max-width:1080px;margin:0 auto;padding:24px}
    .row{display:flex;gap:12px;flex-wrap:wrap}
    input,select,textarea{
      padding:10px 12px;border:1px solid var(--border);border-radius:10px;font:inherit;background:#fff;color:var(--text)
    }
    textarea{min-height:80px; resize:vertical}
    .btn{
      padding:10px 14px;border-radius:999px;border:1px solid var(--primary);
      background:var(--primary);color:#fff;font-weight:700;cursor:pointer
    }
    .btn:hover{background:var(--primary-600);border-color:var(--primary-600)}
    .btn.ghost{background:#fff;color:var(--text);border-color:var(--border)}
    .btn.warn{background:var(--danger);border-color:var(--danger);color:#fff}
    .btn.warn:hover{background:var(--danger-600);border-color:var(--danger-600)}
    .btn:disabled{opacity:.6;cursor:not-allowed}

    .card{
      border:1px solid var(--border);border-radius:16px;background:#fff;padding:18px;margin-bottom:18px;
      box-shadow:0 4px 16px var(--shadow)
    }
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid var(--border);text-align:left;vertical-align:top}
    .top{display:flex;align-items:center;gap:10px;margin-bottom:16px}
    .spacer{flex:1}
    .tag{
      display:inline-flex;align-items:center;padding:6px 10px;border:1px solid var(--border);
      border-radius:999px;background:var(--muted);font-size:12px;color:var(--text)
    }
    .alert{padding:12px 14px;border-radius:10px;margin-bottom:14px}
    .alert.ok{background:var(--ok-bg);border:1px solid var(--ok-bd);color:var(--ok-tx)}
    .alert.err{background:var(--err-bg);border:1px solid var(--err-bd);color:var(--err-tx)}
    details.inline{border:1px dashed var(--border);border-radius:12px;padding:10px;background:#fff}
    details.inline summary{cursor:pointer;font-weight:600;margin-bottom:8px}
    .right{ text-align:right }
    .thumb{width:56px;height:56px;border-radius:10px;border:1px solid var(--border);object-fit:cover;background:var(--muted);display:block}
    .muted{color:var(--muted-text);font-size:12px}
  </style>
</head>
<body>
  <div class="container">
    <div class="top">
      <a href="{{ route('home') }}" style="color:var(--primary-600)">← Home</a>
      <h1 style="margin:0;color:var(--primary-600)">Seller Dashboard</h1>
      <div class="spacer"></div>

      <a class="tag" href="{{ route('seller.orders') }}">📦 Orders</a>

      @auth
      <form action="{{ route('logout') }}" method="POST" style="display:inline;margin-left:8px">
        @csrf
        <button class="btn ghost" type="submit">Logout</button>
      </form>
      @endauth
    </div>

    @if(session('info')) <div class="alert ok">{{ session('info') }}</div> @endif
    @if(session('ok')) <div class="alert ok">{{ session('ok') }}</div> @endif
    @if(session('error')) <div class="alert err">{{ session('error') }}</div> @endif
    @if($errors->any())
      <div class="alert err">
        @foreach($errors->all() as $e)
          <div>{{ $e }}</div>
        @endforeach
      </div>
    @endif

    <div class="card">
      <h2 style="margin-top:0;color:var(--primary-600)">Add Product</h2>
      <form action="{{ route('seller.products.store') }}" method="POST" class="row">
        @csrf
        <input type="text" name="name" placeholder="Product name" value="{{ old('name') }}" required>
        <input type="number" step="0.01" min="0" name="price" placeholder="Price (Rp)" value="{{ old('price') }}" required>
        <input type="number" min="0" name="stock" placeholder="Stock" value="{{ old('stock', 0) }}" required>
        <select name="category" required>
          <option value="" disabled {{ old('category') ? '' : 'selected' }}>Category</option>
          <option value="pastry"      {{ old('category')==='pastry' ? 'selected':'' }}>Pastry</option>
          <option value="cake"        {{ old('category')==='cake' ? 'selected':'' }}>Cake</option>
          <option value="bread"       {{ old('category')==='bread' ? 'selected':'' }}>Bread</option>
          <option value="dessert_box" {{ old('category')==='dessert_box' ? 'selected':'' }}>Dessert Box</option>
        </select>
        <input type="text" name="image_url" placeholder="Image URL (https://... atau /img/file.jpg)" value="{{ old('image_url') }}">
        <textarea name="description" placeholder="Short description (optional)">{{ old('description') }}</textarea>
        <button class="btn" type="submit">Save</button>
      </form>
      <div style="margin-top:10px;color:var(--muted-text);font-size:12px">
      </div>
    </div>

    <div class="card">
      <h2 style="margin-top:0;color:var(--primary-600)">My Catalog</h2>

      @php
        $items = $products instanceof \Illuminate\Pagination\AbstractPaginator ? $products : collect($products);
      @endphp

      <table>
        <thead>
          <tr>
            <th style="width:72px">Image</th>
            <th>Name</th>
            <th style="width:140px">Category</th>
            <th style="width:160px">Price</th>
            <th style="width:100px">Stock</th>
            <th style="width:260px">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($items as $i => $p)
            @php
              $id   = is_object($p) ? $p->id : ($p['id'] ?? null);
              $name = is_object($p) ? $p->name : ($p['name'] ?? '');
              $cat  = is_object($p) ? $p->category : ($p['category'] ?? '');
              $price= is_object($p) ? $p->price : ($p['price'] ?? 0);
              $stock= is_object($p) ? ($p->stock ?? 0) : ($p['stock'] ?? 0);
              $img  = is_object($p) ? ($p->image_url ?? null) : ($p['image_url'] ?? null);
            @endphp
            <tr>
              <td>
                @if($img)
                  <img class="thumb" src="{{ $img }}" alt="{{ $name }}" onerror="this.style.display='none'">
                @else
                  <div class="thumb" style="display:grid;place-items:center;font-size:12px;color:var(--muted-text)">No img</div>
                @endif
                <div class="muted">#{{ $id }}</div>
              </td>
              <td>{{ $name }}</td>
              <td>{{ $cat }}</td>
              <td>Rp {{ number_format($price, 0, ',', '.') }}</td>
              <td>{{ $stock }}</td>
              <td>
                <details class="inline">
                  <summary>Edit</summary>
                  <form action="{{ route('seller.products.update', $id) }}" method="POST" class="row" style="margin-top:8px">
                    @csrf @method('PUT')
                    <input type="text" name="name" value="{{ $name }}" required>
                    <input type="number" step="0.01" min="0" name="price" value="{{ $price }}" required>
                    <input type="number" min="0" name="stock" value="{{ $stock }}" required>
                    <select name="category" required>
                      <option value="pastry"      {{ $cat==='pastry' ? 'selected':'' }}>Pastry</option>
                      <option value="cake"        {{ $cat==='cake' ? 'selected':'' }}>Cake</option>
                      <option value="bread"       {{ $cat==='bread' ? 'selected':'' }}>Bread</option>
                      <option value="dessert_box" {{ $cat==='dessert_box' ? 'selected':'' }}>Dessert Box</option>
                    </select>
                    <input type="text" name="image_url" placeholder="Image URL" value="{{ $img }}">
                    <textarea name="description" placeholder="Short description (optional)">{{ is_object($p)? ($p->description ?? '') : ($p['description'] ?? '') }}</textarea>
                    <button class="btn" type="submit">Update</button>
                  </form>
                  @if($img)
                    <div class="muted" style="margin-top:6px;word-break:break-all">Current: {{ $img }}</div>
                  @endif
                </details>

                <form action="{{ route('seller.products.destroy', $id) }}"
                      method="POST" style="display:inline"
                      onsubmit="return confirm('Delete this product?')">
                  @csrf @method('DELETE')
                  <button class="btn warn" type="submit">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="6">No products yet. Add your first product above.</td></tr>
          @endforelse
        </tbody>
      </table>

      @if($products instanceof \Illuminate\Pagination\AbstractPaginator)
        <div class="right" style="margin-top:12px">
          {{ $products->links() }}
        </div>
      @endif
    </div>

    <div class="card">
      <h2 style="margin-top:0;color:var(--primary-600)">Incoming Orders</h2>
      <p class="muted">Belum ada order. Halaman ini nanti menampilkan order yang masuk dari tabel <code>orders</code>.</p>
    </div>
  </div>
</body>
</html>
