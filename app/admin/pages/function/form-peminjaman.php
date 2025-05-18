<?php
require '../../config/koneksi.php';
session_start();
if($_SESSION['role']!=='Admin') header('Location: ../../login.php');

// Ambil daftar anggota (role Anggota)
\$anggota_res = mysqli_query(\$conn, "SELECT fullname FROM user WHERE role='Anggota'");
// Ambil daftar buku
\$buku_res = mysqli_query(\$conn, "SELECT judul_buku FROM buku");
?>
<!DOCTYPE html>
<html><head><title>Form Peminjaman</title></head><body>
<h2>Form Peminjaman Buku</h2>
<form method="post" action="proses-peminjaman.php">
  <label>Petugas:</label>
  <input type="text" name="petugas" value="<?=htmlspecialchars(\$_SESSION['fullname'])?>" readonly><br>

  <label>Nama Anggota:</label>
  <select name="nama_anggota" required>
    <option value="">-- Pilih Anggota --</option>
    <?php while(\$angg = mysqli_fetch_assoc(\$anggota_res)): ?>
      <option value="<?=htmlspecialchars(\$angg['fullname'])?>"><?=\$angg['fullname']?></option>
    <?php endwhile; ?>
  </select><br>

  <label>Judul Buku:</label>
  <select name="judul_buku" required>
    <option value="">-- Pilih Buku --</option>
    <?php while(\$b = mysqli_fetch_assoc(\$buku_res)): ?>
      <option value="<?=htmlspecialchars(\$b['judul_buku'])?>"><?=\$b['judul_buku']?></option>
    <?php endwhile; ?>
  </select><br>

  <label>Tanggal Peminjaman:</label>
  <input type="date" name="tanggal_peminjaman" required><br>

  <label>Kondisi Buku Saat Dipinjam:</label>
  <select name="kondisi_saat_pinjam" required>
    <option>Baik</option>
    <option>Rusak Ringan</option>
    <option>Rusak Berat</option>
  </select><br>

  <button type="submit">Proses Pinjam</button>
</form>
</body></html>

## 5. Proses Peminjaman (app/admin/proses-peminjaman.php)
. Proses Peminjaman (app/admin/proses-peminjaman.php)
```php
<?php
require '../../config/koneksi.php';
session_start();
if(\$_SESSION['role']!=='Admin') header('Location: ../../login.php');

\$petugas = mysqli_real_escape_string(\$conn, \$_POST['petugas']);
\$nama   = mysqli_real_escape_string(\$conn, \$_POST['nama_anggota']);
\$judul  = mysqli_real_escape_string(\$conn, \$_POST['judul_buku']);
\$tgl    = mysqli_real_escape_string(\$conn, \$_POST['tanggal_peminjaman']);
\$kondisi= mysqli_real_escape_string(\$conn, \$_POST['kondisi_saat_pinjam']);

\$sql = "INSERT INTO peminjaman
         (nama_anggota, judul_buku, tanggal_peminjaman, kondisi_buku_saat_dipinjam, denda, petugas)
         VALUES
         ('\$nama','\$judul','\$tgl','\$kondisi','Tidak ada','\$petugas')";

if(mysqli_query(\$conn, \$sql)) {
  header('Location: list-peminjaman.php?msg=pinjam_ok');
} else {
  echo 'Error: '.mysqli_error(\$conn);
}