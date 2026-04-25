# Sistem Pendukung Keputusan (SPK) Penentuan Penerima Bantuan Bedah Rumah
## Menggunakan Metode Simple Additive Weighting (SAW)

Aplikasi ini adalah platform digital untuk membantu proses seleksi penerima bantuan bedah rumah agar lebih tepat sasaran. Dengan menggunakan metode **SAW**, penilaian tidak lagi subjektif melainkan didasarkan pada perhitungan matematis yang terukur.

---

## 🧐 Apa itu Metode SAW?

Metode **Simple Additive Weighting (SAW)** adalah metode yang paling populer dalam Sistem Pendukung Keputusan. Konsep dasarnya adalah mencari penjumlahan terbobot dari nilai kinerja setiap warga (alternatif) pada semua kriteria.

### 1. Tipe Kriteria: Benefit vs Cost
Dalam sistem ini, setiap kriteria harus ditentukan tipenya karena akan mempengaruhi rumus perhitungan:

*   **Benefit (Semakin Besar Semakin Baik):**
    Digunakan untuk kriteria yang jika nilainya tinggi, warga tersebut dianggap lebih layak dibantu.
    *   *Contoh:* Jumlah tanggungan keluarga (makin banyak tanggungan, makin layak dibantu).
    *   *Rumus:* $\text{Nilai Warga} / \text{Nilai Tertinggi}$
*   **Cost (Semakin Kecil Semakin Baik):**
    Digunakan untuk kriteria yang jika nilainya rendah, warga tersebut dianggap lebih layak dibantu.
    *   *Contoh:* Penghasilan bulanan (makin kecil gaji, makin layak dibantu).
    *   *Rumus:* $\text{Nilai Terendah} / \text{Nilai Warga}$

### 2. Alur Perhitungan (Step-by-Step)
1.  **Input Data:** Petugas menginput data penduduk dan nilai kriterianya.
2.  **Normalisasi Matriks:** Mengubah semua nilai ke skala **0 - 1** agar bisa dibandingkan secara adil (menggunakan rumus Benefit/Cost di atas).
3.  **Pembobotan:** Hasil normalisasi dikalikan dengan **Bobot Kriteria** (misal: Penghasilan 30%, Kondisi Atap 20%).
4.  **Ranking:** Menjumlahkan seluruh hasil perkalian bobot untuk mendapatkan skor akhir. Skor inilah yang menentukan urutan prioritas (Ranking 1 adalah prioritas utama).

---

## 📊 Contoh Kasus: Simulasi Data "Aslam"
Sebagai gambaran bagaimana sistem bekerja, berikut adalah rincian data penduduk bernama **Aslam**:

*   **Penghasilan (Cost - Bobot 30%):** Nilai 3. Normalisasi: $1/3 = 0.33$. Skor: $0.33 \times 0.3 = 0.10$.
*   **Kondisi Dinding (Benefit - Bobot 20%):** Nilai 4. Normalisasi: $4/5 = 0.8$. Skor: $0.8 \times 0.2 = 0.16$.
*   **Kondisi Atap (Benefit - Bobot 20%):** Nilai 5. Normalisasi: $5/5 = 1.0$. Skor: $1.0 \times 0.2 = 0.20$.
*   **Jumlah Tanggungan (Benefit - Bobot 15%):** Nilai 3. Normalisasi: $3/5 = 0.6$. Skor: $0.6 \times 0.15 = 0.09$.
*   **Status Kepemilikan (Benefit - Bobot 15%):** Nilai 3. Normalisasi: $3/5 = 0.6$. Skor: $0.6 \times 0.15 = 0.09$.

**Total Skor Akhir Aslam:** $0.10 + 0.16 + 0.20 + 0.09 + 0.09 = \mathbf{0.64}$ (Status: **Layak**).

---

## 📑 Manajemen Status & Rekomendasi

Sistem menyediakan label otomatis dan manual untuk memudahkan pengambilan keputusan:

1.  **Rekomendasi Sistem (Otomatis):**
    *   **Layak:** Jika Skor Akhir $\ge 0.5$.
    *   **Tidak Layak:** Jika Skor Akhir $< 0.5$.
2.  **Status Penduduk (Alur Kerja):**
    *   **Menunggu:** Data baru diinput, belum ada penilaian.
    *   **Diproses:** Petugas sudah mulai mengisi nilai kriteria (sedang dievaluasi).
    *   **Diterima / Ditolak:** Keputusan final yang diinput secara manual oleh **Admin** atau **Pimpinan**.

---

## 👥 Peran Pengguna (Roles)

*   **Admin:** Mengelola seluruh data, kriteria, bobot, user, dan dapat merubah status final.
*   **Evaluator:** Menginput data penduduk, mengunggah foto rumah, dan memberikan nilai kriteria.
*   **Pimpinan:** Melihat laporan ranking, mengunduh PDF/Excel, dan memberikan keputusan akhir "Terima" atau "Tolak".

---

## 📁 Struktur File Penting

*   `app/Services/SawService.php`: Inti dari algoritma perhitungan SAW.
*   `app/Http/Controllers/HasilController.php`: Menangani tampilan ranking dan ekspor laporan.
*   `resources/views/penduduk/show.blade.php`: Halaman detail untuk melihat data lengkap, foto, dan tombol keputusan status.
*   `database/migrations/`: Struktur tabel database (termasuk penambahan kolom `is_active` dan `status`).

---

## 🚀 Cara Menjalankan Aplikasi
1.  `composer install` & `npm install`
2.  Setup database di `.env`
3.  `php artisan migrate --seed`
4.  `php artisan storage:link` (Penting agar foto rumah muncul)
5.  `php artisan serve`
