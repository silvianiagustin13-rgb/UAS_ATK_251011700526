<?php
include 'koneksi.php';
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php"); exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pengadaan      = trim($_POST['id_pengadaan']);
    $nama_barang       = trim($_POST['nama_barang']);
    $kategori          = $_POST['kategori'];
    $jumlah            = intval($_POST['jumlah']);
    $satuan            = trim($_POST['satuan']);
    $harga_satuan      = floatval(str_replace(['.', ','], ['', '.'], $_POST['harga_satuan']));
    $supplier          = trim($_POST['supplier']);
    $tanggal_pengadaan = $_POST['tanggal_pengadaan'];
    $status            = $_POST['status'];

    $cek = mysqli_query($koneksi, "SELECT id_pengadaan FROM tb_pengadaan_atk WHERE id_pengadaan='" . mysqli_real_escape_string($koneksi, $id_pengadaan) . "'");
    if (mysqli_num_rows($cek) > 0) {
        header("Location: tambah.php?error=Kode+pengadaan+sudah+digunakan!");
        exit();
    }

    $foto_barang = '';
    if (!empty($_FILES['foto_barang']['name'])) {
        $allowed = ['jpg','jpeg','png','webp','gif'];
        $ext     = strtolower(pathinfo($_FILES['foto_barang']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            header("Location: tambah.php?error=Format+foto+tidak+didukung!(jpg,png,webp)");
            exit();
        }
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        $foto_barang = time() . '_' . basename($_FILES['foto_barang']['name']);
        if (!move_uploaded_file($_FILES['foto_barang']['tmp_name'], $target_dir . $foto_barang)) {
            header("Location: tambah.php?error=Gagal+mengupload+foto!");
            exit();
        }
    } else {
        header("Location: tambah.php?error=Foto/bukti+barang+wajib+diupload!");
        exit();
    }

    $query = "INSERT INTO tb_pengadaan_atk
              (id_pengadaan, nama_barang, kategori, jumlah, satuan, harga_satuan, supplier, tanggal_pengadaan, foto_barang, status)
              VALUES (?,?,?,?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "sssisdssss", $id_pengadaan, $nama_barang, $kategori, $jumlah, $satuan, $harga_satuan, $supplier, $tanggal_pengadaan, $foto_barang, $status);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: pengadaan.php?success=Data+pengadaan+ATK+berhasil+ditambahkan!");
        exit();
    } else {
        header("Location: tambah.php?error=Gagal+menyimpan+data:+" . urlencode(mysqli_error($koneksi)));
        exit();
    }
}

include 'partials/header.php';
?>

<div class="main-content">

<script>
$(document).ready(function(){
    toastr.options = { positionClass:'toast-top-right', timeOut:4000, progressBar:true, closeButton:true };
    <?php if (isset($_GET['error'])): ?>
        toastr.error('<?php echo addslashes(strip_tags($_GET['error'])); ?>', 'Gagal!');
    <?php endif; ?>
});
</script>

<div class="page-header">
    <h1><i class="fas fa-plus-circle me-2" style="color:#0E8C7F;"></i>Tambah <span>Data Pengadaan ATK</span></h1>
    <a href="pengadaan.php" style="color:#94A3B8; font-weight:700; text-decoration:none;">&larr; Kembali ke daftar</a>
</div>

<div class="os-card">
  <div class="card-body">
    <form action="" method="post" enctype="multipart/form-data">
      <div class="row g-3">

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-hashtag me-1" style="color:#0E8C7F;"></i>Kode Pengadaan <span style="color:red">*</span></label>
            <input type="text" name="id_pengadaan" class="form-control" placeholder="Contoh: ATK004" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-box me-1" style="color:#0E8C7F;"></i>Nama Barang <span style="color:red">*</span></label>
            <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Stapler Besar" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-th-list me-1" style="color:#0E8C7F;"></i>Kategori <span style="color:red">*</span></label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <?php foreach(['Alat Tulis','Kertas & Cetak','Peralatan Kantor','Tinta & Toner','Map & Arsip','Lainnya'] as $k): ?>
                <option value="<?php echo $k; ?>"><?php echo $k; ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div class="row g-2">
            <div class="col-7">
              <div class="mb-3">
                <label class="form-label"><i class="fas fa-cubes me-1" style="color:#0E8C7F;"></i>Jumlah <span style="color:red">*</span></label>
                <input type="number" name="jumlah" class="form-control" placeholder="10" min="1" required>
              </div>
            </div>
            <div class="col-5">
              <div class="mb-3">
                <label class="form-label">Satuan <span style="color:red">*</span></label>
                <input type="text" name="satuan" class="form-control" placeholder="pcs / box / rim" value="pcs" required>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-toggle-on me-1" style="color:#0E8C7F;"></i>Status</label>
            <select name="status" class="form-control">
                <option value="Diajukan">Diajukan</option>
                <option value="Disetujui">Disetujui</option>
                <option value="Diterima">Diterima</option>
                <option value="Ditolak">Ditolak</option>
            </select>
          </div>
        </div>

        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-money-bill-wave me-1" style="color:#0E8C7F;"></i>Harga Satuan (Rp) <span style="color:red">*</span></label>
            <input type="number" name="harga_satuan" class="form-control" placeholder="15000" min="0" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-truck-field me-1" style="color:#0E8C7F;"></i>Supplier</label>
            <input type="text" name="supplier" class="form-control" placeholder="Contoh: CV Sumber Makmur">
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-calendar me-1" style="color:#0E8C7F;"></i>Tanggal Pengadaan <span style="color:red">*</span></label>
            <input type="date" name="tanggal_pengadaan" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label"><i class="fas fa-camera me-1" style="color:#0E8C7F;"></i>Foto / Bukti Barang <span style="color:red">*</span></label>
            <input type="file" name="foto_barang" id="foto_barang" class="form-control" accept="image/*" required>
            <div class="img-preview-wrap mt-2" id="preview-wrap">
                <span style="color:#B8C4D6; font-size:0.85rem;"><i class="fas fa-image me-1"></i>Preview foto akan muncul di sini</span>
            </div>
          </div>
        </div>

        <div class="col-12 d-flex gap-2 justify-content-end">
            <a href="pengadaan.php" style="padding:10px 24px; border-radius:10px; background:#EEF2F9; color:#14305C; font-weight:700; text-decoration:none;">
                <i class="fas fa-times me-1"></i> Batal
            </a>
            <button type="submit" class="btn-os-primary">
                <i class="fas fa-save me-1"></i> Simpan Data
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
                '<img src="' + ev.target.result + '" alt="Preview">';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include 'partials/footer.php'; ?>
