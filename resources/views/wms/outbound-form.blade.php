<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>WMS — {{ isset($outbound) ? 'Edit' : 'Tambah' }} Outgoing</title>
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
    <h1 class="h4 fw-bold mb-4">{{ isset($outbound) ? 'Edit' : 'Tambah' }} Outgoing</h1>
    
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="card shadow-sm">
      <div class="card-body p-4">
        <form action="{{ isset($outbound) ? route('outbound.update', $outbound->id) : route('outbound.store') }}" method="POST">
          @csrf
          @if(isset($outbound)) @method('PUT') @endif
          
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">ID Outgoing</label>
              <input type="text" name="outgoing_id" class="form-control @error('outgoing_id') is-invalid @enderror" 
                     value="{{ old('outgoing_id', $outbound->outgoing_id ?? '') }}" placeholder="OUT-001" required>
              @error('outgoing_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">No DO</label>
              <select name="no_do" class="form-select @error('no_do') is-invalid @enderror" id="deliveryOrderSelect" required>
                <option value="">Pilih No. DO</option>
                @foreach($deliveryOrders as $do)
                  <option value="{{ $do->no_do }}" data-details="{{ json_encode($do->details->map(function($d) { return ['sku' => $d->sku, 'name' => $d->product->name ?? '', 'quantity' => $d->quantity]; })) }}" {{ old('no_do', $outbound->no_do ?? '') == $do->no_do ? 'selected' : '' }}>
                    {{ $do->no_do }} - {{ $do->driver }} ({{ $do->date->format('d-m-Y') }})
                  </option>
                @endforeach
              </select>
              @error('no_do') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Tanggal</label>
              <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                     value="{{ old('date', isset($outbound) ? $outbound->date->format('Y-m-d') : '') }}" required>
              @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-12">
              <label class="form-label">Informasi Barang</label>
              <div class="p-3 bg-light rounded border" id="itemsInfo" style="min-height: 100px; max-height: 200px; overflow-y: auto;">
                <small class="text-muted">Pilih DO untuk melihat daftar barang</small>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Nett (Kg)</label>
              <input type="number" name="nett" step="0.01" class="form-control @error('nett') is-invalid @enderror" 
                     value="{{ old('nett', $outbound->nett ?? 0) }}" min="0" required>
              @error('nett') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Gross (Kg)</label>
              <input type="number" name="gross" step="0.01" class="form-control @error('gross') is-invalid @enderror" 
                     value="{{ old('gross', $outbound->gross ?? 0) }}" min="0" required>
              @error('gross') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Status</label>
              <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="Pending" {{ old('status', $outbound->status ?? 'Pending') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Dikirim" {{ old('status', $outbound->status ?? '') == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="Dibatalkan" {{ old('status', $outbound->status ?? '') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
              </select>
              @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
              <small class="text-muted">Catatan: Ubah status ke "Dikirim" akan otomatis mengurangi stok barang</small>
            </div>
            <div class="col-12">
              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save me-2"></i> Simpan</button>
                <a href="{{ route('outbound.index') }}" class="btn btn-outline-secondary">Batal</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>

  <footer class="custom-footer text-light mt-5 py-3"><div class="container"><small>WMS — Laravel</small></div></footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Handle DO selection to display items
    document.getElementById('deliveryOrderSelect').addEventListener('change', function() {
      const selectedOption = this.options[this.selectedIndex];
      const itemsInfo = document.getElementById('itemsInfo');
      
      if (!this.value) {
        itemsInfo.innerHTML = '<small class="text-muted">Pilih DO untuk melihat daftar barang</small>';
        return;
      }
      
      const detailsJson = selectedOption.getAttribute('data-details');
      if (!detailsJson) {
        itemsInfo.innerHTML = '<small class="text-muted">Tidak ada data barang</small>';
        return;
      }
      
      try {
        const details = JSON.parse(detailsJson);
        let html = '<div><strong class="d-block mb-2">Barang:</strong>';
        
        details.forEach(detail => {
          html += `<div class="mb-1"><small><strong>${detail.name}</strong> — ${detail.quantity}</small></div>`;
        });
        
        html += '</div>';
        itemsInfo.innerHTML = html;
      } catch (e) {
        itemsInfo.innerHTML = '<small class="text-muted">Error loading items</small>';
      }
    });
    
    // Trigger on page load if DO is already selected
    window.addEventListener('load', function() {
      document.getElementById('deliveryOrderSelect').dispatchEvent(new Event('change'));
    });
  </script>
</body>
</html>
