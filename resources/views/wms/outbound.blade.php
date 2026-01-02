<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>WMS — Outbound</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style> body{background:#f5f6f8; min-height:100vh; display:flex; flex-direction:column; margin:0; padding-bottom:60px;} footer.custom-footer{ background:linear-gradient(90deg,#0b132b,#0f2a4d); color:#cfe7ff; position:fixed; bottom:0; left:0; right:0; width:100%; z-index:1000;} </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold d-flex align-items-center" href="/wms/dashboard"><i class="fa-solid fa-warehouse me-2"></i>PT. Benua Kertas</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="{{ route('wms.dashboard') }}"><i class="fa-solid fa-chart-line me-1"></i> Dashboard</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('inventory.index') }}"><i class="fa-solid fa-boxes-stacked me-1"></i> Barang</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('delivery-orders.index') }}"><i class="fa-solid fa-truck me-1"></i> DO</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('inbound.index') }}"><i class="fa-solid fa-arrow-down-wide-short me-1"></i> Incoming</a></li>
          <li class="nav-item"><a class="nav-link active" href="{{ route('outbound.index') }}"><i class="fa-solid fa-arrow-up-wide-short me-1"></i> Outgoing</a></li>
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

  <main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div><h1 class="h4 fw-bold mb-0">Outgoing</h1><small class="text-muted">Barang keluar dari gudang</small></div>
      <div>
        <a href="{{ route('outbound.printAll') }}" class="btn btn-info btn-sm" target="_blank"><i class="fa-solid fa-print me-1"></i> Cetak Semua</a>
        <a href="{{ route('outbound.create') }}" class="btn btn-success btn-sm"><i class="fa-solid fa-plus me-1"></i> Tambah Outgoing</a>
      </div>
    </div>

    @if($message = Session::get('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="card shadow-sm">
      <div class="card-body p-3">
        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead class="table-dark">
              <tr>
                <th>ID Outgoing</th>
                <th>No DO</th>
                <th>Informasi Barang</th>
                <th>Tanggal</th>
                <th>Nett (Kg)</th>
                <th>Gross (Kg)</th>
                <th>Status</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($outbounds as $outbound)
                <tr>
                  <td>{{ $outbound->outgoing_id }}</td>
                  <td>{{ $outbound->no_do }}</td>
                  <td>
                    <small>
                      @forelse($outbound->deliveryOrder->details ?? [] as $detail)
                        <div>{{ $detail->product->name ?? $detail->sku }} ({{ $detail->quantity }} pcs)</div>
                      @empty
                        <span class="text-muted">-</span>
                      @endforelse
                    </small>
                  </td>
                  <td>{{ $outbound->date->format('d-m-Y') }}</td>
                  <td>{{ number_format($outbound->nett, 2) }}</td>
                  <td>{{ number_format($outbound->gross, 2) }}</td>
                  <td>
                    <span class="badge {{ $outbound->status == 'Dikirim' ? 'bg-success' : ($outbound->status == 'Dibatalkan' ? 'bg-danger' : 'bg-warning') }}">
                      {{ $outbound->status }}
                    </span>
                  </td>
                  <td class="text-center">
                    <a href="{{ route('outbound.print', $outbound->id) }}" class="btn btn-sm btn-primary" target="_blank"><i class="fa-solid fa-print"></i></a>
                    <a href="{{ route('outbound.edit', $outbound->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-edit"></i></a>
                    <form action="{{ route('outbound.destroy', $outbound->id) }}" method="POST" style="display:inline;">
                      @method('DELETE')
                      @csrf
                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin?')"><i class="fa-solid fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr><td colspan="8" class="text-center text-muted">Tidak ada data outgoing</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <footer class="custom-footer text-light mt-5 py-3"><div class="container d-flex justify-content-between"><div>WMS — Outbound</div></div></footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
