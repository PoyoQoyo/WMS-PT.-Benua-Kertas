<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>WMS — Tambah Produk</title>
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
          <li class="nav-item"><a class="nav-link active" href="{{ route('inventory.index') }}"><i class="fa-solid fa-boxes-stacked me-1"></i> Barang</a></li>
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

  <main class="container mt-4">
    <h1 class="h4 fw-bold mb-4">{{ isset($product) ? 'Edit Barang' : 'Tambah Barang' }}</h1>
    
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
        <form action="{{ isset($product) ? route('inventory.update', $product->id) : route('inventory.store') }}" method="POST">
          @csrf
          @if(isset($product)) @method('PUT') @endif
          
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">SKU</label>
              <input type="text" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                     value="{{ old('sku', $product->sku ?? '') }}" required>
              @error('sku') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Nama Barang</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                     value="{{ old('name', $product->name ?? '') }}" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Kategori</label>
              <input type="text" name="category" class="form-control @error('category') is-invalid @enderror" 
                     value="{{ old('category', $product->category ?? '') }}" required>
              @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label">Satuan</label>
              <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                <option value="">Pilih Satuan</option>
                <option value="Rim" {{ old('unit', $product->unit ?? '') == 'Rim' ? 'selected' : '' }}>Rim</option>
                <option value="Box" {{ old('unit', $product->unit ?? '') == 'Box' ? 'selected' : '' }}>Box</option>
                <option value="Pcs" {{ old('unit', $product->unit ?? '') == 'Pcs' ? 'selected' : '' }}>Pcs</option>
                <option value="Kg" {{ old('unit', $product->unit ?? '') == 'Kg' ? 'selected' : '' }}>Kg</option>
                <option value="Liter" {{ old('unit', $product->unit ?? '') == 'Liter' ? 'selected' : '' }}>Liter</option>
              </select>
              @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Stok Awal</label>
              <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                     value="{{ old('stock', $product->stock ?? 0) }}" min="0" required>
              @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Lokasi</label>
              <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                     value="{{ old('location', $product->location ?? '') }}" placeholder="Contoh: Rak A1" required>
              @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
              <label class="form-label">Status</label>
              <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                <option value="Aktif" {{ old('status', $product->status ?? 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Tidak Aktif" {{ old('status', $product->status ?? '') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
              </select>
              @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-12">
              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save me-2"></i> Simpan</button>
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">Batal</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </main>

  <footer class="custom-footer text-light mt-5 py-3"><div class="container"><small>WMS — Laravel</small></div></footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
