<?php
require '../../config/koneksi.php';
session_start();
if(\$_SESSION['role']!=='Admin') header('Location: ../../login.php');

// Ambil data peminjaman yang belum dikembalikan
\$res = mysqli_query(\$conn, "SELECT id_peminjaman, nama_anggota, judul_buku FROM peminjaman WHERE tanggal_pengembalian IS NULL OR tanggal_pengembalian = ''");
\$data = mysqli_fetch_assoc(mysqli_query(\$conn, "SELECT * FROM peminjaman WHERE id_peminjaman = " . intval(\$_GET['id'])));
?>
<!DOCTYPE html>
<html><head><title>Form Pengembalian</title></head><body>
<h2>Form Pengembalian Buku</h2>
<form method="post" action="proses-pengembalian.php">
  <label>Pilih Transaksi:</label>
  <select name="id" required>
    <option value="">-- Pilih Peminjaman --</option>
    <?php while(\$d = mysqli_fetch_assoc(\$res)): ?>
      <option value="<?=\$d['id_peminjaman']?>">
        <?=\$d['nama_anggota']?> | <?=\$d['judul_buku']?>
      </option>
    <?php endwhile; ?>
  </select><br>

  <label>Petugas:</label>
  <input type="text" name="petugas" value="<?=htmlspecialchars(\$_SESSION['fullname'])?>" readonly><br>

  <label>Tanggal Pengembalian:</label>
  <input type="date" name="tanggal_pengembalian" required><br>

  <label>Kondisi Saat Kembali:</label>
  <select name="kondisi_saat_kembali" required>
    <option>Baik</option>
    <option>Rusak Ringan</option>
    <option>Rusak Berat</option>
  </select><br>

  <label>Denda:</label>
  <input type="text" name="denda" placeholder="Jika ada"><br>

  <button type="submit">Proses Kembali</button>
</form>
</body></html>

## 8. Proses Pengembalian (app/admin/proses-pengembalian.php)
. Proses Pengembalian (app/admin/proses-pengembalian.php)
```php
<?php
require '../../config/koneksi.php';
session_start();
if(\$_SESSION['role']!=='Admin') header('Location: ../../login.php');

\$id      = intval(\$_POST['id']);
\$petugas = mysqli_real_escape_string(\$conn, \$_POST['petugas']);
\$tgl     = mysqli_real_escape_string(\$conn, \$_POST['tanggal_pengembalian']);
\$kondisi = mysqli_real_escape_string(\$conn, \$_POST['kondisi_saat_kembali']);
\$denda   = mysqli_real_escape_string(\$conn, \$_POST['denda']);

\$sql = "UPDATE peminjaman SET
          tanggal_pengembalian = '\$tgl',
          kondisi_buku_saat_dikembalikan = '\$kondisi',
          denda = '\$denda',
          petugas = '\$petugas'
        WHERE id_peminjaman = \$id";

if(mysqli_query(\$conn, \$sql)) {
  header('Location: list-peminjaman.php?msg=kembali_ok');
} else {
  echo 'Error: '.mysqli_error(\$conn);
}