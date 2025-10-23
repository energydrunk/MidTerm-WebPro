<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register — SweetCrumbs</title>
  <style>
    :root{
      --bg:#FFFAEB;          
      --text:#5B4636;       
      --text-muted:#7A695B;   
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
    }

    a{
      text-decoration:none;
      color:var(--primary-600);
    }
    a:hover{text-decoration:underline}

    .wrap{
      max-width:520px;
      margin:40px auto;
      padding:0 18px;
    }

    .card{
      border:1px solid var(--border);
      border-radius:16px;
      background:#fff;
      padding:22px;
    }

    .title{
      font-weight:800;
      font-size:24px;
      margin:0 0 8px;
      color:var(--primary-600);
    }

    .sub{
      opacity:.8;
      margin-bottom:14px;
    }

    .row{
      display:flex;
      flex-direction:column;
      gap:10px;
      margin-top:10px;
    }

    label{
      font-size:13px;
      color:var(--text-muted);
    }

    input{
      padding:10px 12px;
      border:1px solid var(--border);
      border-radius:10px;
      background:var(--muted);
      color:var(--text);
    }

    .btn{
      display:inline-block;
      padding:10px 16px;
      border-radius:999px;
      border:1px solid var(--primary);
      background:var(--primary);
      color:#fff;
      font-weight:700;
      transition:.2s;
    }

    .btn:hover{
      background:var(--primary-600);
      border-color:var(--primary-600);
    }

    .btn.secondary{
      background:#fff;
      color:var(--text);
      border-color:var(--border);
    }

    .err{
      color:#8A1F1F; 
      font-size:13px;
      margin-top:6px;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="title">Create account</div>
      <div class="sub">Join SweetCrumbs to place orders</div>

      <form action="{{ route('register.submit') }}" method="POST">
        @csrf
        <div class="row">
          <label for="name">Name</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
          @error('name') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label for="email">Email</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required>
          @error('email') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label for="password">Password</label>
          <input id="password" type="password" name="password" required>
          @error('password') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="row">
          <label for="password_confirmation">Confirm Password</label>
          <input id="password_confirmation" type="password" name="password_confirmation" required>
        </div>

        <div class="row" style="margin-top:12px;gap:12px">
          <button class="btn" type="submit">Create account</button>
          <a class="btn secondary" href="{{ route('home') }}">Back</a>
        </div>

        <div class="row" style="margin-top:12px">
          <span>Already have an account? <a href="{{ route('login') }}">Login</a>.</span>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
