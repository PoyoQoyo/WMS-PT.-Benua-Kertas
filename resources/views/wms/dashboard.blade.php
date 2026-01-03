<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>WMS — Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    :root{ --kpi-blue:#1976d2; --kpi-green:#1aa251; --kpi-orange:#ff8a1f; }
    body{ background:#f5f6f8; min-height:100vh; display:flex; flex-direction:column; margin:0; padding-bottom:60px; }
    .kpi-icon{ width:56px;height:56px;border-radius:12px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;box-shadow:0 6px 18px rgba(0,0,0,0.08); }
    .kpi-blue{ background:var(--kpi-blue); } .kpi-green{ background:var(--kpi-green);} .kpi-orange{ background:var(--kpi-orange); }
    footer.custom-footer{ background:linear-gradient(90deg,#0b132b,#0f2a4d); color:#cfe7ff; position:fixed; bottom:0; left:0; right:0; width:100%; z-index:1000; }
  </style>
</head>
<body>
  <!-- navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="/wms/dashboard"><i class="fa-solid fa-warehouse me-2"></i>PT. Benua Kertas</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link active" href="{{ route('wms.dashboard') }}"><i class="fa-solid fa-chart-line me-1"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('inventory.index') }}"><i class="fa-solid fa-boxes-stacked me-1"></i> Barang</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('delivery-orders.index') }}"><i class="fa-solid fa-truck me-1"></i> DO</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('inbound.index') }}"><i class="fa-solid fa-arrow-down-wide-short me-1"></i> Incoming</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('outbound.index') }}"><i class="fa-solid fa-arrow-up-wide-short me-1"></i> Outgoing</a></li>
          <li class="nav-item ms-3">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-light"><i class="fa-solid fa-sign-out-alt me-1"></i> Logout</button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="container mt-4">
    <div><h1 class="h4 fw-bold mb-0">Dashboard</h1><small class="text-muted">Ringkasan operasional</small></div>
  </header>

  <main class="container mt-4">
    <div class="row g-3">
      <div class="col-12 col-md-4">
        <a href="{{ route('inbound.index') }}" class="text-reset text-decoration-none d-block h-100">
          <div class="card p-3 shadow-sm h-100">
            <div class="d-flex justify-content-between">
              <div><h6 class="mb-1 fw-semibold text-muted">Jumlah Barang Masuk</h6><div class="fs-3 fw-bold">{{ number_format($incomingItems) }} Barang</div></div>
              <div class="kpi-icon kpi-blue"><i class="fa-solid fa-arrow-down-wide-short"></i></div>
            </div>
            <small class="text-muted">Menghitung hanya status Diterima</small>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-4">
        <a href="{{ route('outbound.index') }}" class="text-reset text-decoration-none d-block h-100">
          <div class="card p-3 shadow-sm h-100">
            <div class="d-flex justify-content-between">
              <div><h6 class="mb-1 fw-semibold text-muted">Jumlah Barang Keluar</h6><div class="fs-3 fw-bold">{{ number_format($outgoingItems) }} Barang</div></div>
              <div class="kpi-icon kpi-green"><i class="fa-solid fa-arrow-up-wide-short"></i></div>
            </div>
            <small class="text-muted">Menghitung hanya status Dikirim</small>
          </div>
        </a>
      </div>
      <div class="col-12 col-md-4">
        <a href="{{ route('inventory.index') }}" class="text-reset text-decoration-none d-block h-100">
          <div class="card p-3 shadow-sm h-100">
            <div class="d-flex justify-content-between">
              <div><h6 class="mb-1 fw-semibold text-muted">Total Jenis Barang</h6><div class="fs-3 fw-bold">{{ number_format($skuCount) }} Jenis</div></div>
              <div class="kpi-icon kpi-orange"><i class="fa-solid fa-boxes-stacked"></i></div>
            </div>
            <small class="text-muted">{{ number_format($newSkuThisWeek) }} jenis baru minggu ini</small>
          </div>
        </a>
      </div>
    </div>

    <section class="mt-4">
      <div class="card shadow-sm">
        <div class="card-body p-3">
          <h5 class="fw-bold mb-3">Recent Activity</h5>
          <ul class="list-group">
            @forelse($recentActivities as $activity)
              <li class="list-group-item">{{ $activity }}</li>
            @empty
              <li class="list-group-item text-muted">Tidak ada aktivitas terbaru</li>
            @endforelse
          </ul>
        </div>
      </div>
    </section>
  </main>

  <footer class="custom-footer text-light mt-5 py-3">
    <div class="container d-flex justify-content-between"><div><strong>WMS</strong> — Dashboard</div></div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
