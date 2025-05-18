<?php
require '../../config/koneksi.php';
session_start();
if(\$_SESSION['role']!=='Admin') header('Location: ../../login.php');
\$res = mysqli_query(\$conn, 'SELECT * FROM peminjaman');
?>
<!DOCTYPE html>
<html><head><title>Data Peminjaman</title></head><body>
<h2>Data Peminjaman</h2>
<table border=1>
<tr><th>ID</th><th>Anggota</th><th>Buku</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Petugas</th><th>Aksi</th></tr>
<?php while(\$r=mysqli_fetch_assoc(\$res)): ?>
<tr>
  <td><?=\$r['id_peminjaman']?></td>
  <td><?=htmlspecialchars(\$r['nama_anggota'])?></td>
  <td><?=htmlspecialchars(\$r['judul_buku'])?></td>
  <td><?=\$r['tanggal_peminjaman']?></td>
  <td><?=\$r['tanggal_pengembalian']?:'-'?></td>
  <td><?=htmlspecialchars(\$r['petugas']?:'-')?></td>
  <td>
    <?php if(empty(\$r['tanggal_pengembalian'])): ?>
      <a href="form-pengembalian.php?id=<?=\$r['id_peminjaman']?>">Kembalikan</a>
    <?php else: ?>
      Selesai
    <?php endif; ?>
  </td>
</tr>
<?php endwhile; ?>
</table>
</body></html>