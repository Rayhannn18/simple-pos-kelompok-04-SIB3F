\# Pengukuran Memori: get() vs lazyById()



\## Tujuan

Pengujian ini dilakukan untuk membandingkan penggunaan memori ketika mengambil data transaksi menggunakan `get()` dan `lazyById()` pada Laravel.



\## Hasil Pengujian



Dataset yang digunakan adalah 52.500 baris pada tabel `transactions`.



| Metode | Jumlah Data | Peak Memory | Hasil |

|---|---:|---:|---|

| `Transaction::lazyById()` | 52.500 | 70,5 MB | Berhasil |

| `Transaction::get()` | 52.500 | Tidak berhasil diukur | Gagal karena melewati batas memori PHP sebesar 128 MB |



\## Kesimpulan



Berdasarkan hasil pengujian, metode `lazyById()` berhasil membaca 52.500 data transaksi dengan penggunaan peak memory sebesar 70,5 MB. Sementara itu, metode `get()` mengalami kegagalan karena penggunaan memori melewati batas yang ditetapkan PHP, yaitu 128 MB. Hasil ini menunjukkan bahwa `lazyById()` lebih sesuai untuk membaca data dalam jumlah besar pada kondisi pengujian ini karena data diproses secara bertahap.





