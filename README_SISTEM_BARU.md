# Warehouse Management System (WMS) - tweek13

## 📋 Alur Sistem Baru

Sistem WMS telah diperbarui dengan alur halaman sebagai berikut:

### 1️⃣ **Halaman BARANG** (Stok Awal = 0)
- URL: `/wms/inventory`
- Menampilkan daftar semua barang dengan informasi:
  - No, SKU, Nama Barang, Kategori, Satuan, Stok, Lokasi, Status
- Stok awal dimulai dari 0
- Data sample:
  - BK-A4-80: Kertas A4 80 gsm (Rim) - Rak A1
  - BK-A3-100: Kertas A3 100 gsm (Rim) - Rak A2

### 2️⃣ **Halaman DO (Delivery Order)**
- URL: `/wms/delivery-orders`
- Menampilkan rencana pengiriman barang
- Satu DO dapat memiliki multiple SKU
- Data ditampilkan: No DO, SKU, Jumlah, Satuan, Supir, Tanggal
- **PENTING**: DO hanya menyimpan rencana, BELUM mempengaruhi stok barang

### 3️⃣ **Halaman INCOMING**
- URL: `/wms/inbound`
- Mencatat penerimaan barang yang masuk gudang
- Data: ID Incoming, No Container, No DO, Tanggal Masuk, Nett, Gross, Status
- **Proses Otomatis**:
  1. Sistem membaca No DO yang dipilih
  2. Sistem mengambil detail SKU & jumlah dari tabel DO
  3. Sistem mencocokkan dengan tabel Barang
  4. Ketika status diubah menjadi **"Diterima"**, stok barang OTOMATIS bertambah

### 4️⃣ **Update Stok Otomatis**
Contoh:
- BK-A4-80: 0 → +100 → **100**
- BK-A3-100: 0 → +50 → **50**

### 5️⃣ **Halaman OUTGOING**
- URL: `/wms/outbound`
- Mencatat barang keluar dari gudang
- Data: ID Outgoing, Tanggal, SKU, Jumlah Keluar, No DO, Status
- **Proses Otomatis**:
  - Ketika status diubah menjadi **"Dikirim"**, stok barang OTOMATIS berkurang

### 6️⃣ **Stok Akhir**
Sistem menghitung:
```
Stok Akhir = Stok Awal + Incoming - Outgoing
```

Contoh:
- BK-A4-80: 0 + 100 - 40 = **60**
- BK-A3-100: 0 + 50 - 20 = **30**

---

## 🗄️ Struktur Database

### Tabel: `products` (BARANG)
```sql
- id
- sku (unique)
- name
- category
- unit
- stock (default: 0)
- location
- status (Aktif/Tidak Aktif)
```

### Tabel: `delivery_orders` (DO Header)
```sql
- id
- no_do (unique)
- driver
- date
- notes
```

### Tabel: `delivery_order_details` (DO Detail)
```sql
- id
- delivery_order_id (FK)
- sku (FK to products)
- quantity
- unit
```

### Tabel: `inbounds` (INCOMING)
```sql
- id
- incoming_id (unique)
- container_no
- delivery_order_id (FK)
- date_received
- nett
- gross
- status (Pending/Diterima/Ditolak)
```

### Tabel: `outbounds` (OUTGOING)
```sql
- id
- outgoing_id (unique)
- date
- sku (FK to products)
- quantity
- no_do
- status (Pending/Dikirim/Dibatalkan)
```

---

## 🚀 Cara Menjalankan

### 1. Migrasi Database
```bash
php artisan migrate:fresh
```

### 2. Seed Data Sample
```bash
php artisan db:seed --class=SampleDataSeeder
```

### 3. Jalankan Server
```bash
php artisan serve
```

### 4. Akses Aplikasi
Buka browser: `http://localhost:8000`

---

## 🔄 Alur Kerja Sistem

### **Scenario: Incoming Barang**
1. Admin membuat barang baru di halaman **BARANG** dengan stok = 0
2. Admin membuat **DO** (Delivery Order) dengan detail barang yang akan datang
3. Ketika barang tiba, admin membuat **INCOMING** dan pilih DO yang sesuai
4. Admin ubah status menjadi **"Diterima"**
5. ✅ Stok barang OTOMATIS bertambah sesuai quantity di DO

### **Scenario: Outgoing Barang**
1. Admin membuat **OUTGOING** dan pilih SKU barang
2. Masukkan quantity yang akan keluar
3. Admin ubah status menjadi **"Dikirim"**
4. ✅ Stok barang OTOMATIS berkurang

---

## ⚠️ Penting!

1. **DO tidak mempengaruhi stok** - hanya sebagai rencana pengiriman
2. **Incoming dengan status "Diterima"** yang menambah stok
3. **Outgoing dengan status "Dikirim"** yang mengurangi stok
4. Sistem validasi stok saat outgoing - tidak bisa mengirim melebihi stok tersedia

---

## 📁 File Penting

### Models:
- `app/Models/Product.php`
- `app/Models/DeliveryOrder.php`
- `app/Models/DeliveryOrderDetail.php`
- `app/Models/Inbound.php`
- `app/Models/Outbound.php`

### Controllers:
- `app/Http/Controllers/InventoryController.php`
- `app/Http/Controllers/DeliveryOrderController.php`
- `app/Http/Controllers/InboundController.php`
- `app/Http/Controllers/OutboundController.php`

### Views:
- `resources/views/wms/inventory.blade.php`
- `resources/views/wms/delivery-order.blade.php`
- `resources/views/wms/inbound.blade.php`
- `resources/views/wms/outbound.blade.php`

### Routes:
- `routes/web.php`

---

## ✅ Fitur Utama

✔️ Manajemen Barang dengan SKU unik  
✔️ Delivery Order dengan multiple items  
✔️ Incoming dengan auto-update stok  
✔️ Outgoing dengan validasi stok  
✔️ Status tracking (Pending/Diterima/Dikirim)  
✔️ Relasi antar tabel yang jelas  
✔️ UI responsif dengan Bootstrap 5  

---

Sistem telah siap digunakan! 🎉
