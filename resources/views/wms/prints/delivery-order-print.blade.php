<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Cetak - DO {{ $deliveryOrder->no_do }}</title>
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
      margin-bottom: 20px;
      border-bottom: 2px solid #333;
      padding-bottom: 15px;
    }
    .company-name {
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }
    .document-title {
      font-size: 18px;
      font-weight: bold;
      margin-top: 10px;
      color: #555;
    }
    .print-date {
      color: #666;
      font-size: 12px;
      margin-top: 10px;
    }
    .info-section {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      font-size: 13px;
    }
    .info-block {
      flex: 1;
    }
    .info-label {
      font-weight: bold;
      color: #333;
      width: 100px;
    }
    .info-value {
      color: #555;
      word-break: break-word;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    thead {
      background-color: #333;
      color: white;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }
    th {
      font-weight: bold;
      text-align: center;
    }
    td {
      font-size: 13px;
    }
    .text-right {
      text-align: right;
    }
    .footer {
      margin-top: 30px;
      padding-top: 15px;
      border-top: 1px solid #ddd;
      text-align: center;
      font-size: 12px;
      color: #999;
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
  </style>
</head>
<body>
  <div class="header">
    <div class="company-name">PT. Benua Kertas</div>
    <div class="document-title">Delivery Order (DO)</div>
    <div class="print-date">Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</div>
  </div>

  <div class="info-section">
    <div class="info-block">
      <div><span class="info-label">No DO:</span> <span class="info-value">{{ $deliveryOrder->no_do }}</span></div>
      <div><span class="info-label">Tipe:</span> <span class="info-value">{{ $deliveryOrder->type }}</span></div>
      <div><span class="info-label">Supir:</span> <span class="info-value">{{ $deliveryOrder->driver }}</span></div>
      <div><span class="info-label">Tanggal:</span> <span class="info-value">{{ $deliveryOrder->date->format('d-m-Y') }}</span></div>
      @if($deliveryOrder->notes)
        <div><span class="info-label">Catatan:</span> <span class="info-value">{{ $deliveryOrder->notes }}</span></div>
      @endif
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 5%;">No</th>
        <th style="width: 20%;">SKU</th>
        <th style="width: 40%;">Nama Barang</th>
        <th style="width: 15%;">Jumlah</th>
        <th style="width: 20%;">Satuan</th>
      </tr>
    </thead>
    <tbody>
      @forelse($deliveryOrder->details as $index => $detail)
        <tr>
          <td class="text-right">{{ $index + 1 }}</td>
          <td>{{ $detail->sku }}</td>
          <td>{{ $detail->product->name ?? '-' }}</td>
          <td class="text-right">{{ number_format($detail->quantity, 2) }}</td>
          <td>{{ $detail->unit }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center">Tidak ada detail barang</td>
        </tr>
      @endforelse
    </tbody>
  </table>

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
