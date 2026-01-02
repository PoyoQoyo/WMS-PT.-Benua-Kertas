<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Cetak - {{ $product->name }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 20px;
    }
    @media print {
      body {
        padding: 0;
      }
      .no-print {
        display: none;
      }
    }
    .header {
      text-align: center;
      margin-bottom: 30px;
      border-bottom: 2px solid #333;
      padding-bottom: 15px;
    }
    .company-name {
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }
    .print-date {
      color: #666;
      font-size: 12px;
      margin-top: 10px;
    }
    .section-title {
      font-size: 16px;
      font-weight: bold;
      margin-top: 20px;
      margin-bottom: 15px;
      border-bottom: 1px solid #999;
      padding-bottom: 8px;
    }
    .info-row {
      display: flex;
      margin-bottom: 12px;
      font-size: 14px;
    }
    .info-label {
      width: 150px;
      font-weight: bold;
      color: #333;
    }
    .info-value {
      flex: 1;
      color: #555;
      word-break: break-word;
    }
    .button-group {
      margin-top: 20px;
      display: flex;
      gap: 10px;
    }
    .btn-print {
      background-color: #007bff;
      color: white;
      padding: 8px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 14px;
    }
    .btn-print:hover {
      background-color: #0056b3;
    }
    .footer {
      margin-top: 40px;
      padding-top: 15px;
      border-top: 1px solid #ddd;
      text-align: center;
      font-size: 12px;
      color: #999;
    }
  </style>
</head>
<body>
  <div class="header">
    <div class="company-name">PT. Benua Kertas</div>
    <div class="print-date">Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</div>
  </div>

  <div class="section-title">Data Barang</div>
  
  <div class="info-row">
    <div class="info-label">SKU</div>
    <div class="info-value">{{ $product->sku }}</div>
  </div>

  <div class="info-row">
    <div class="info-label">Nama Barang</div>
    <div class="info-value">{{ $product->name }}</div>
  </div>

  <div class="info-row">
    <div class="info-label">Kategori</div>
    <div class="info-value">{{ $product->category }}</div>
  </div>

  <div class="info-row">
    <div class="info-label">Satuan</div>
    <div class="info-value">{{ $product->unit }}</div>
  </div>

  <div class="info-row">
    <div class="info-label">Stok</div>
    <div class="info-value">{{ $product->stock }}</div>
  </div>

  <div class="info-row">
    <div class="info-label">Lokasi</div>
    <div class="info-value">{{ $product->location }}</div>
  </div>

  <div class="info-row">
    <div class="info-label">Status</div>
    <div class="info-value">
      <span class="badge {{ $product->status == 'Aktif' ? 'bg-success' : 'bg-secondary' }}">
        {{ $product->status }}
      </span>
    </div>
  </div>

  <div class="footer">
    Dokumen ini telah dicetak dari Sistem WMS PT. Benua Kertas
  </div>

  <div class="button-group no-print">
    <button class="btn-print" onclick="window.print()">Cetak</button>
    <button class="btn-print" onclick="window.history.back()" style="background-color: #6c757d;">Kembali</button>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
