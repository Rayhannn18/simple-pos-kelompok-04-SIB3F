## Jobsheet 6 Tugas 5 – Validasi Total Transaksi

Validasi numeric pada field total belum cukup untuk mencegah manipulasi total. Validasi numeric hanya mengecek apakah nilai yang dikirim berupa angka atau bukan. Jadi, meskipun nilainya sudah berupa angka, pengguna masih bisa mengubah jumlah totalnya sebelum dikirim ke server.

Contohnya, harga barang Rp10.000 dan jumlah yang dibeli 2, seharusnya totalnya Rp20.000. Tetapi pengguna bisa saja mengubah nilai total menjadi Rp1.000. Nilai tersebut tetap dianggap valid karena Rp1.000 masih berupa angka.

Karena itu, total sebaiknya tidak langsung dipercaya dari form. Server perlu menghitung ulang total berdasarkan harga produk yang ada di database dan jumlah barang yang dikirim. Dengan begitu, total yang digunakan sesuai dengan harga dan jumlah barang sebenarnya, bukan dari nilai yang bisa diubah oleh pengguna.


## Jobsheet 7 Tugas 4 - Skenario Uji Manual RBAC

### 1. Role Admin

**Langkah:**
1. Buka halaman `/login`.
2. Masukkan email `admin@pos.test`.
3. Masukkan password `password`.
4. Klik tombol **Masuk**.
5. Buka halaman `/products`.
6. Buka halaman `/transactions`.

**Hasil yang diharapkan:**
- Admin berhasil login.
- Admin dapat membuka halaman `/products`.
- Admin dapat membuka halaman `/transactions`.

### 2. Role Manager

**Langkah:**
1. Buka halaman `/login`.
2. Masukkan email `manager@pos.test`.
3. Masukkan password `password`.
4. Klik tombol **Masuk**.
5. Buka halaman `/transactions`.
6. Buka halaman `/products`.

**Hasil yang diharapkan:**
- Manager berhasil login.
- Manager dapat membuka halaman `/transactions`.
- Manager tidak dapat membuka halaman `/products` dan mendapatkan respons **403 Forbidden**.

### 3. Role Kasir

**Langkah:**
1. Buka halaman `/login`.
2. Masukkan email `kasir@pos.test`.
3. Masukkan password `password`.
4. Klik tombol **Masuk**.
5. Buka halaman `/pos`.
6. Buka halaman `/transactions`.
7. Buka halaman `/products`.

**Hasil yang diharapkan:**
- Kasir berhasil login.
- Kasir dapat membuka halaman `/pos`.
- Kasir dapat membuka halaman `/transactions`.
- Kasir tidak dapat membuka halaman `/products` dan mendapatkan respons **403 Forbidden**.