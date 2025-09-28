<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Hillside Laundry</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { min-height:100vh; }
    .sidebar { width:260px; background:#1f2937; color:white; min-height:100vh; }
    .sidebar a { color: #d1d5db; display:block; padding:12px; text-decoration:none; }
    .sidebar a:hover, .sidebar a.active { background:#374151; color:white; }
    .user-info { background: #374151; padding: 10px; margin: -12px -12px 12px -12px; }
  </style>
</head>
<body>
<div class="d-flex">
  <aside class="sidebar p-3">
    <h4>Hillside LaundryShop</h4>
    <div class="user-info">
      <small>Welcome, {{ Auth::user()->name }}</small>
      <br>
      <small>Role: {{ Auth::user()->role }}</small>
    </div>
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active':'' }}">
      <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="{{ route('pos.index') }}" class="{{ request()->routeIs('pos.*') ? 'active':'' }}">
      <i class="bi bi-calculator-fill"></i> POS
    </a>
    <a href="{{ route('inventory.page') }}" class="{{ request()->routeIs('inventory.*') ? 'active':'' }}">
      <i class="bi bi-boxes"></i> Inventory
    </a>
    <a href="{{ route('customers.page') }}" class="{{ request()->routeIs('customers.*') ? 'active':'' }}">
      <i class="bi bi-people"></i> Customers
    </a>
    <a href="{{ route('sales.page') }}" class="{{ request()->routeIs('sales.*') ? 'active':'' }}">
      <i class="bi bi-graph-up"></i> Sales Report
    </a>
    
    <div class="mt-4">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-light btn-sm w-100">
          <i class="bi bi-box-arrow-right"></i> Logout
        </button>
      </form>
    </div>
    
    <div class="mt-4 text-muted small">DB: Hillside_Laundryshop3</div>
  </aside>

  <main class="flex-grow-1 p-4">
    @yield('content')
  </main>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
</script>
</body>
</html>