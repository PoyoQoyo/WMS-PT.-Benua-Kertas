<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>WMS — Detail Incoming</title>
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
          <li class="nav-item"><a class="nav-link active" href="{{ route('inbound.index') }}"><i class="fa-solid fa-arrow-down-wide-short me-1"></i> Incoming</a></li>
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

  <main class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h4 fw-bold mb-0">Detail Incoming</h1>
      <a href="{{ route('inbound.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrow-left me-1"></i> Kembali</a>
    </div>

    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Informasi Incoming</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <td width="40%"><strong>ID Incoming</strong></td>
                <td>: {{ $inbound->incoming_id }}</td>
              </tr>
              <tr>
                <td><strong>No Container</strong></td>
                <td>: {{ $inbound->container_no }}</td>
              </tr>
              <tr>
                <td><strong>No DO</strong></td>
                <td>: {{ $inbound->deliveryOrder->no_do }}</td>
              </tr>
              <tr>
                <td><strong>Tanggal Diterima</strong></td>
                <td>: {{ $inbound->date_received->format('d-m-Y') }}</td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <td width="40%"><strong>Nett</strong></td>
                <td>: {{ number_format($inbound->nett, 2) }} Kg</td>
              </tr>
              <tr>
                <td><strong>Gross</strong></td>
                <td>: {{ number_format($inbound->gross, 2) }} Kg</td>
              </tr>
              <tr>
                <td><strong>Status</strong></td>
                <td>: 
                  <span class="badge {{ $inbound->status == 'Diterima' ? 'bg-success' : ($inbound->status == 'Ditolak' ? 'bg-danger' : 'bg-warning') }}">
                    {{ $inbound->status }}
                  </span>
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Detail Barang (dari DO: {{ $inbound->deliveryOrder->no_do }})</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-striped">
            <thead class="table-dark">
              <tr>
                <th>No</th>
                <th>SKU</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Jumlah</th>
                <th>Satuan</th>
              </tr>
            </thead>
            <tbody>
              @foreach($inbound->deliveryOrder->details as $index => $detail)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $detail->sku }}</td>
                  <td>{{ $detail->product->name ?? '-' }}</td>
                  <td>{{ $detail->product->category ?? '-' }}</td>
                  <td>{{ $detail->quantity }}</td>
                  <td>{{ $detail->unit }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </main>

  <footer class="custom-footer text-light mt-5 py-3"><div class="container"><small>WMS — Laravel</small></div></footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
