<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>WMS — {{ isset($deliveryOrder) ? 'Edit' : 'Tambah' }} Delivery Order</title>
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
          <li class="nav-item"><a class="nav-link active" href="{{ route('delivery-orders.index') }}"><i class="fa-solid fa-truck me-1"></i> DO</a></li>
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

  <main class="container mt-4">
    <h1 class="h4 fw-bold mb-4">{{ isset($deliveryOrder) ? 'Edit' : 'Tambah' }} Delivery Order</h1>
    
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
        <form action="{{ isset($deliveryOrder) ? route('delivery-orders.update', $deliveryOrder->id) : route('delivery-orders.store') }}" method="POST" id="doForm">
          @csrf
          @if(isset($deliveryOrder)) @method('PUT') @endif
          
          <div class="row g-3 mb-4">
            <div class="col-md-4">
              <label class="form-label">No DO</label>
              <input type="text" name="no_do" class="form-control @error('no_do') is-invalid @enderror" 
                     value="{{ old('no_do', $deliveryOrder->no_do ?? '') }}" placeholder="DO-001" required>
              @error('no_do') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Supir</label>
              <input type="text" name="driver" class="form-control @error('driver') is-invalid @enderror" 
                     value="{{ old('driver', $deliveryOrder->driver ?? '') }}" required>
              @error('driver') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Tanggal</label>
              <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                     value="{{ old('date', isset($deliveryOrder) ? $deliveryOrder->date->format('Y-m-d') : '') }}" required>
              @error('date') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
              <label class="form-label">Catatan</label>
              <textarea name="notes" class="form-control" rows="2">{{ old('notes', $deliveryOrder->notes ?? '') }}</textarea>
            </div>
          </div>

          <hr>
          <h5 class="mb-3">Detail Barang</h5>

          <div id="itemsContainer">
            @if(isset($deliveryOrder) && $deliveryOrder->details->count() > 0)
              @foreach($deliveryOrder->details as $index => $detail)
                <div class="row g-2 mb-2 item-row">
                  <div class="col-md-6">
                    <select name="items[{{ $index }}][sku]" class="form-select" required>
                      <option value="">Pilih Barang</option>
                      @foreach($products as $product)
                        <option value="{{ $product->sku }}" {{ $detail->sku == $product->sku ? 'selected' : '' }}>
                          {{ $product->sku }} - {{ $product->name }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <div class="col-md-4">
                    <input type="number" name="items[{{ $index }}][quantity]" class="form-control" placeholder="Jumlah" value="{{ $detail->quantity }}" min="1" required>
                  </div>
                  <div class="col-md-2">
                    <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)"><i class="fa-solid fa-trash"></i></button>
                  </div>
                </div>
              @endforeach
            @else
              <div class="row g-2 mb-2 item-row">
                <div class="col-md-6">
                  <select name="items[0][sku]" class="form-select" required>
                    <option value="">Pilih Barang</option>
                    @foreach($products as $product)
                      <option value="{{ $product->sku }}">{{ $product->sku }} - {{ $product->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <input type="number" name="items[0][quantity]" class="form-control" placeholder="Jumlah" min="1" required>
                </div>
                <div class="col-md-2">
                  <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)"><i class="fa-solid fa-trash"></i></button>
                </div>
              </div>
            @endif
          </div>

          <button type="button" class="btn btn-sm btn-secondary mb-3" onclick="addItem()"><i class="fa-solid fa-plus me-1"></i> Tambah Barang</button>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success"><i class="fa-solid fa-save me-2"></i> Simpan</button>
            <a href="{{ route('delivery-orders.index') }}" class="btn btn-outline-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </main>

  <footer class="custom-footer text-light mt-5 py-3"><div class="container"><small>WMS — Laravel</small></div></footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let itemIndex = {{ isset($deliveryOrder) ? $deliveryOrder->details->count() : 1 }};
    const products = @json($products);

    function addItem() {
      const container = document.getElementById('itemsContainer');
      const newRow = document.createElement('div');
      newRow.className = 'row g-2 mb-2 item-row';
      newRow.innerHTML = `
        <div class="col-md-6">
          <select name="items[${itemIndex}][sku]" class="form-select" required>
            <option value="">Pilih Barang</option>
            ${products.map(p => `<option value="${p.sku}">${p.sku} - ${p.name}</option>`).join('')}
          </select>
        </div>
        <div class="col-md-4">
          <input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Jumlah" min="1" required>
        </div>
        <div class="col-md-2">
          <button type="button" class="btn btn-danger w-100" onclick="removeItem(this)"><i class="fa-solid fa-trash"></i></button>
        </div>
      `;
      container.appendChild(newRow);
      itemIndex++;
    }

    function removeItem(button) {
      const rows = document.querySelectorAll('.item-row');
      if (rows.length > 1) {
        button.closest('.item-row').remove();
      } else {
        alert('Minimal harus ada 1 barang');
      }
    }
  </script>
</body>
</html>
