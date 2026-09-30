## Jobsheet 6 Tugas 5 – Validasi Total Transaksi

Validasi numeric pada field total belum cukup untuk mencegah manipulasi total. Validasi numeric hanya mengecek apakah nilai yang dikirim berupa angka atau bukan. Jadi, meskipun nilainya sudah berupa angka, pengguna masih bisa mengubah jumlah totalnya sebelum dikirim ke server.

Contohnya, harga barang Rp10.000 dan jumlah yang dibeli 2, seharusnya totalnya Rp20.000. Tetapi pengguna bisa saja mengubah nilai total menjadi Rp1.000. Nilai tersebut tetap dianggap valid karena Rp1.000 masih berupa angka.

Karena itu, total sebaiknya tidak langsung dipercaya dari form. Server perlu menghitung ulang total berdasarkan harga produk yang ada di database dan jumlah barang yang dikirim. Dengan begitu, total yang digunakan sesuai dengan harga dan jumlah barang sebenarnya, bukan dari nilai yang bisa diubah oleh pengguna.