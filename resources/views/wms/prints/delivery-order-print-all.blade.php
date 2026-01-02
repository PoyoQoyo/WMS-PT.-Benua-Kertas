<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Cetak Semua - Delivery Order</title>
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
      .page-break {
        page-break-after: always;
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
    .do-section {
      margin-top: 20px;
      page-break-inside: avoid;
    }
    .do-header {
      background-color: #f5f5f5;
      padding: 10px;
      border-left: 4px solid #333;
      margin-bottom: 10px;
      font-size: 13px;
    }
    .do-info {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-bottom: 10px;
      font-size: 12px;
    }
    .info-label {
      font-weight: bold;
      color: #333;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
      font-size: 12px;
    }
    thead {
      background-color: #555;
      color: white;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 6px;
      text-align: left;
    }
    th {
      font-weight: bold;
      text-align: center;
    }
    .text-right {
      text-align: right;
    }
    .text-center {
      text-align: center;
    }
    .summary {
      margin-top: 20px;
      padding: 10px;
      background-color: #f0f0f0;
      border-left: 4px solid #333;
      font-size: 13px;
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
    <div class="document-title">Laporan Delivery Order (DO) - Semua Data</div>
    <div class="print-date">Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</div>
  </div>

  @forelse($deliveryOrders as $index => $do)
    <div class="do-section">
      <div class="do-header">
        <strong>DO #{{ $index + 1 }}</strong> - {{ $do->no_do }}
      </div>

      <div class="do-info">
        <div>
          <span class="info-label">No DO:</span> {{ $do->no_do }}<br>
          <span class="info-label">Tipe:</span> {{ $do->type }}<br>
          <span class="info-label">Tanggal:</span> {{ $do->date->format('d-m-Y') }}
        </div>
        <div>
          <span class="info-label">Supir:</span> {{ $do->driver }}<br>
          @if($do->notes)
            <span class="info-label">Catatan:</span> {{ $do->notes }}
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
          @forelse($do->details as $dindex => $detail)
            <tr>
              <td class="text-center">{{ $dindex + 1 }}</td>
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
    </div>
  @empty
    <div class="alert alert-info">Tidak ada delivery order</div>
  @endforelse

  @if($deliveryOrders->count() > 0)
    <div class="summary">
      <strong>Ringkasan:</strong><br>
      Total Delivery Order: <strong>{{ $deliveryOrders->count() }}</strong><br>
      Total Detail Barang: <strong>{{ $deliveryOrders->sum(fn($do) => $do->details->count()) }}</strong>
    </div>
  @endif

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
