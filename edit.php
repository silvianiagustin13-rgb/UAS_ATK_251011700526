<?php
ob_start();
include 'koneksi.php';
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php"); exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pengadaan      = $_POST['id_pengadaan'];
    $nama_barang       = trim($_POST['nama_barang']);
    $kategori          = $_POST['kategori'];
    $jumlah            = intval($_POST['jumlah']);
    $satuan            = trim($_POST['satuan']);
    $harga_satuan      = floatval($_POST['harga_satuan']);
    $supplier          = trim($_POST['supplier']);
    $tanggal_pengadaan = $_POST['tanggal_pengadaan'];
    $status            = $_POST['status'];
    $foto_lama         = $_POST['foto_lama'];

    $foto_barang = $foto_lama;

    if (!empty($_FILES['foto_barang']['name'])) {
        $allowed = ['jpg','jpeg','png','webp','gif'];
        $ext     = strtolower(pathinfo($_FILES['foto_barang']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            header("Location: edit.php?id=$id_pengadaan&error=Format+foto+tidak+didukung!");
            exit();
        }
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $foto_barang = time() . '_' . basename($_FILES['foto_barang']['name']);
        if (!move_uploaded_file($_FILES['foto_barang']['tmp_name'], $target_dir . $foto_barang)) {
            header("Location: edit.php?id=$id_pengadaan&error=Gagal+upload+foto!");
            exit();
        }
        if ($foto_lama && file_exists("uploads/$foto_lama")) {
            unlink("uploads/$foto_lama");
        }
    }

    $query = "UPDATE tb_pengadaan_atk SET
              nama_barang=?, kategori=?, jumlah=?, satuan=?, harga_satuan=?,
              supplier=?, tanggal_pengadaan=?, foto_barang=?, status=?
              WHERE id_pengadaan=?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssisdssss", $nama_barang, $kategori, $jumlah, $satuan, $harga_satuan, $supplier, $tanggal_pengadaan, $foto_barang, $status, $id_pengadaan);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: pengadaan.php?success=Data+pengadaan+ATK+berhasil+diperbarui!");
        exit();
    } else {
        header("Location: edit.php?id=$id_pengadaan&error=Gagal+menyimpan+perubahan!");
        exit();
    }
}

include 'partials/header.php';

$row = null;
if (isset($_GET['id'])) {
    $id  = mysqli_real_escape_string($koneksi, $_GET['id']);
    $res = mysqli_query($koneksi, "SELECT * FROM tb_pengadaan_atk WHERE id_pengadaan='$id'");
    $row = mysqli_fetch_assoc($res);
    if (!$row) {
        header("Location: pengadaan.php?error=Data+tidak+ditemukan!");
        exit();
    }
}
?>

<div class="main-content">

<script>
$(document).ready(function(){
    toastr.options = { positionClass:'toast-top-right', timeOut:4000, progressBar:true, closeButton:true };
    <?php if (isset($_GET['error'])): ?>
        toastr.error('<?php echo htmlspecialchars($_GET['error']); ?>', 'Gagal!');
    <?php endif; ?>
});
</script>

<div class="page-header">
    <h1><i class="fas fa-edit me-2" style="color:#F2A93B;"></i>Edit <span>Data Pengadaan ATK</span></h1>
    <a href="pengadaan.php" style="color:#94A3B8; font-weight:700; text-decoration:none;">&larr; Kembali ke daftar</a>
</div>

<div class="os-card">
  <div class="card-body">
    <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id_pengadaan" value="<?php echo htmlspecialchars($row['id_pengadaan']); ?>">
      <input type="hidden" name="foto_lama" value="<?php echo htmlspecialchars($row['foto_barang']); ?>">

      <div class="row g-3">
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-hashtag me-1" style="color:#F2A93B;"></i>Kode Pengadaan</label>
            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['id_pengadaan']); ?>" disabled
                   style="background:#F1F5F9; color:#94A3B8;">
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-box me-1" style="color:#F2A93B;"></i>Nama Barang <span style="color:red">*</span></label>
            <input type="text" name="nama_barang" class="form-control"
                   value="<?php echo htmlspecialchars($row['nama_barang']); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-th-list me-1" style="color:#F2A93B;"></i>Kategori <span style="color:red">*</span></label>
            <select name="kategori" class="form-control" required>
                <?php foreach(['Alat Tulis','Kertas & Cetak','Peralatan Kantor','Tinta & Toner','Map & Arsip','Lainnya'] as $k): ?>
                <option value="<?php echo $k; ?>" <?php echo ($row['kategori']==$k)?'selected':''; ?>><?php echo $k; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="row g-2">
            <div class="col-7">
              <div class="mb-3">
                <label class="form-label"><i class="fas fa-cubes me-1" style="color:#F2A93B;"></i>Jumlah</label>
                <input type="number" name="jumlah" class="form-control" value="<?php echo $row['jumlah']; ?>" min="1" required>
              </div>
            </div>
            <div class="col-5">
              <div class="mb-3">
                <label class="form-label">Satuan</label>
                <input type="text" name="satuan" class="form-control" value="<?php echo htmlspecialchars($row['satuan']); ?>" required>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-toggle-on me-1" style="color:#F2A93B;"></i>Status</label>
            <select name="status" class="form-control">
                <?php foreach(['Diajukan','Disetujui','Diterima','Ditolak'] as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo ($row['status']==$s)?'selected':''; ?>><?php echo $s; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-money-bill-wave me-1" style="color:#F2A93B;"></i>Harga Satuan (Rp)</label>
            <input type="number" name="harga_satuan" class="form-control" value="<?php echo $row['harga_satuan']; ?>" min="0" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-truck-field me-1" style="color:#F2A93B;"></i>Supplier</label>
            <input type="text" name="supplier" class="form-control" value="<?php echo htmlspecialchars($row['supplier']); ?>">
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-calendar me-1" style="color:#F2A93B;"></i>Tanggal Pengadaan</label>
            <input type="date" name="tanggal_pengadaan" class="form-control" value="<?php echo $row['tanggal_pengadaan']; ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-camera me-1" style="color:#F2A93B;"></i>Foto / Bukti Barang
              <small style="color:#94A3B8;">(kosongkan jika tidak ingin mengubah)</small>
            </label>
            <input type="file" name="foto_barang" id="foto_barang" class="form-control" accept="image/*">
            <div class="img-preview-wrap mt-2" id="preview-wrap">
                <?php if ($row['foto_barang'] && file_exists('uploads/'.$row['foto_barang'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['foto_barang']); ?>" alt="foto" id="preview-img">
                <?php else: ?>
                    <span style="color:#B8C4D6; font-size:0.85rem;">Tidak ada foto</span>
                <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="col-12 d-flex gap-2 justify-content-end">
            <a href="pengadaan.php" style="padding:10px 24px; border-radius:10px; background:#EEF2F9; color:#14305C; font-weight:700; text-decoration:none;">
                <i class="fas fa-times me-1"></i> Batal
            </a>
            <button type="submit" class="btn-os-amber">
                <i class="fas fa-save me-1"></i> Simpan Perubahan
            </button>
        </div>
      </div>
    </form>
  </div>
</div>

</div>

<script>
document.getElementById('foto_barang').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('preview-wrap').innerHTML =
                '<img src="' + ev.target.result + '" alt="Preview" id="preview-img">';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include 'partials/footer.php'; ?>
