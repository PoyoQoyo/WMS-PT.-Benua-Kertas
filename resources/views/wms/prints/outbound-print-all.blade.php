<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Cetak Semua - Outgoing</title>
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
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
      font-size: 13px;
    }
    thead {
      background-color: #333;
      color: white;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
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
    .status-badge {
      display: inline-block;
      padding: 3px 6px;
      border-radius: 3px;
      font-size: 11px;
      font-weight: bold;
      color: white;
    }
    .status-dikirim {
      background-color: #28a745;
    }
    .status-pending {
      background-color: #ffc107;
      color: #333;
    }
    .status-dibatalkan {
      background-color: #dc3545;
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
    <div class="document-title">Laporan Outgoing (Barang Keluar) - Semua Data</div>
    <div class="print-date">Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 10%;">ID Outgoing</th>
        <th style="width: 12%;">No DO</th>
        <th style="width: 25%;">Informasi Barang</th>
        <th style="width: 13%;">Tanggal</th>
        <th style="width: 10%;">Nett (Kg)</th>
        <th style="width: 10%;">Gross (Kg)</th>
        <th style="width: 10%;">Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($outbounds as $outbound)
        <tr>
          <td>{{ $outbound->outgoing_id }}</td>
          <td>{{ $outbound->no_do }}</td>
          <td>
            @if($outbound->deliveryOrder && $outbound->deliveryOrder->details && $outbound->deliveryOrder->details->count() > 0)
              @foreach($outbound->deliveryOrder->details as $detail)
                <div>{{ $detail->product->name ?? $detail->sku }} ({{ $detail->quantity ?? 0 }} pcs)</div>
              @endforeach
            @else
              -
            @endif
          </td>
          <td>{{ $outbound->date->format('d-m-Y') }}</td>
          <td class="text-right">{{ number_format($outbound->nett, 2) }}</td>
          <td class="text-right">{{ number_format($outbound->gross, 2) }}</td>
          <td class="text-center">
            <span class="status-badge @if($outbound->status == 'Dikirim') status-dikirim @elseif($outbound->status == 'Dibatalkan') status-dibatalkan @else status-pending @endif">
              {{ $outbound->status }}
            </span>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center">Tidak ada data outgoing</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  @if($outbounds->count() > 0)
    <div class="summary">
      <strong>Ringkasan:</strong><br>
      Total Outgoing: <strong>{{ $outbounds->count() }}</strong><br>
      Total Nett: <strong>{{ number_format($outbounds->sum('nett'), 2) }} Kg</strong><br>
      Total Gross: <strong>{{ number_format($outbounds->sum('gross'), 2) }} Kg</strong><br>
      Status Dikirim: <strong>{{ $outbounds->where('status', 'Dikirim')->count() }}</strong> | 
      Pending: <strong>{{ $outbounds->where('status', 'Pending')->count() }}</strong> | 
      Dibatalkan: <strong>{{ $outbounds->where('status', 'Dibatalkan')->count() }}</strong>
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
