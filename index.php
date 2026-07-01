<?php include 'partials/header.php'; ?>

<div class="main-content">

<?php
if (isset($_GET['success'])): ?>
<script>
$(document).ready(function(){
    toastr.options = { positionClass:'toast-top-right', timeOut:3500, progressBar:true, closeButton:true };
    toastr.success('<?php echo addslashes(strip_tags($_GET['success'])); ?>', 'Berhasil!');
});
</script>
<?php endif; if (isset($_GET['error'])): ?>
<script>
$(document).ready(function(){
    toastr.options = { positionClass:'toast-top-right', timeOut:4000, progressBar:true, closeButton:true };
    toastr.error('<?php echo addslashes(strip_tags($_GET['error'])); ?>', 'Gagal!');
});
</script>
<?php endif; ?>

<?php
include 'koneksi.php';
$total     = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM tb_pengadaan_atk"))[0];
$diterima  = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM tb_pengadaan_atk WHERE status='Diterima'"))[0];
$diajukan  = mysqli_fetch_row(mysqli_query($koneksi, "SELECT COUNT(*) FROM tb_pengadaan_atk WHERE status='Diajukan'"))[0];
$total_nilai_row = mysqli_fetch_row(mysqli_query($koneksi, "SELECT SUM(jumlah * harga_satuan) FROM tb_pengadaan_atk"));
$total_nilai = $total_nilai_row[0] ?? 0;
?>

<div class="page-header">
    <div>
        <h1>
            <span>Dashboard</span>
            <span style="font-size:0.9rem; font-weight:600; color:#0E8C7F; margin-left:8px;">OfficeStock</span>
        </h1>
        <p style="margin:6px 0 0; font-size:0.85rem; color:#94A3B8;">
            <?php echo date('l, d F Y'); ?> &nbsp;·&nbsp; Selamat datang, <strong style="color:#14305C;"><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
        </p>
    </div>
    <a href="pengadaan.php" class="btn-os-primary"><i class="fas fa-boxes-stacked"></i> Kelola Pengadaan</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="stat-card-new" style="background:linear-gradient(135deg,#14305C 0%,#2C4D85 100%);">
            <div class="stat-card-inner">
                <div class="stat-card-icon-wrap"><i class="fas fa-boxes-stacked"></i></div>
                <div class="stat-card-info">
                    <div class="stat-num"><?php echo $total; ?></div>
                    <div class="stat-lbl">Total Item Pengadaan</div>
                </div>
            </div>
            <div class="stat-card-bar"><div class="stat-bar-fill" style="width:100%; background:rgba(255,255,255,0.4);"></div></div>
            <a href="pengadaan.php" class="stat-card-link">Lihat semua data <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-new" style="background:linear-gradient(135deg,#0E8C7F 0%,#0B6E64 100%);">
            <div class="stat-card-inner">
                <div class="stat-card-icon-wrap"><i class="fas fa-check-circle"></i></div>
                <div class="stat-card-info">
                    <div class="stat-num"><?php echo $diterima; ?></div>
                    <div class="stat-lbl">Sudah Diterima</div>
                </div>
            </div>
            <div class="stat-card-bar">
                <?php $pct = $total > 0 ? ($diterima/$total)*100 : 0; ?>
                <div class="stat-bar-fill" style="width:<?php echo $pct; ?>%; background:rgba(255,255,255,0.4);"></div>
            </div>
            <a href="pengadaan.php" class="stat-card-link"><?php echo round($pct); ?>% dari total <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-new" style="background:linear-gradient(135deg,#F2A93B 0%,#E08E1B 100%);">
            <div class="stat-card-inner">
                <div class="stat-card-icon-wrap"><i class="fas fa-hourglass-half"></i></div>
                <div class="stat-card-info">
                    <div class="stat-num"><?php echo $diajukan; ?></div>
                    <div class="stat-lbl">Menunggu Persetujuan</div>
                </div>
            </div>
            <div class="stat-card-bar">
                <?php $pct2 = $total > 0 ? ($diajukan/$total)*100 : 0; ?>
                <div class="stat-bar-fill" style="width:<?php echo $pct2; ?>%; background:rgba(255,255,255,0.4);"></div>
            </div>
            <a href="pengadaan.php" class="stat-card-link">Perlu ditindaklanjuti <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card-new" style="background:linear-gradient(135deg,#64748B 0%,#475569 100%);">
            <div class="stat-card-inner">
                <div class="stat-card-icon-wrap"><i class="fas fa-coins"></i></div>
                <div class="stat-card-info">
                    <div class="stat-num" style="font-size:1.3rem;">Rp<?php echo number_format($total_nilai,0,',','.'); ?></div>
                    <div class="stat-lbl">Total Nilai Pengadaan</div>
                </div>
            </div>
            <div class="stat-card-bar"><div class="stat-bar-fill" style="width:100%; background:rgba(255,255,255,0.4);"></div></div>
            <a href="report.php" target="_blank" class="stat-card-link">Cetak laporan <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<div class="os-card">
    <div class="card-body">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px;">
            <h5 style="font-weight:800; color:#14305C; margin:0;">Pengadaan Terbaru</h5>
            <a href="pengadaan.php" class="btn-os-primary" style="font-size:0.82rem;"><i class="fas fa-list"></i> Lihat Semua</a>
        </div>
        <div style="overflow-x:auto;">
        <table class="os-table w-100">
            <thead>
                <tr><th>Kode</th><th>Nama Barang</th><th>Kategori</th><th>Jumlah</th><th>Total Harga</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php
            $badge     = ['Diterima'=>'badge-green','Diajukan'=>'badge-amber','Disetujui'=>'badge-teal','Ditolak'=>'badge-red'];
            $cat_color = ['Alat Tulis'=>'badge-navy','Kertas & Cetak'=>'badge-teal','Peralatan Kantor'=>'badge-amber','Tinta & Toner'=>'badge-navy','Map & Arsip'=>'badge-teal','Lainnya'=>'badge-gray'];
            $q = "SELECT * FROM tb_pengadaan_atk ORDER BY tanggal_pengadaan DESC LIMIT 5";
            $r = mysqli_query($koneksi, $q);
            while ($row = mysqli_fetch_assoc($r)):
            ?>
            <tr>
                <td><strong style="color:#14305C;"><?php echo $row['id_pengadaan']; ?></strong></td>
                <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                <td><span class="badge-os <?php echo $cat_color[$row['kategori']] ?? 'badge-navy'; ?>"><?php echo $row['kategori']; ?></span></td>
                <td><?php echo $row['jumlah']; ?> <?php echo htmlspecialchars($row['satuan']); ?></td>
                <td><strong>Rp<?php echo number_format($row['jumlah'] * $row['harga_satuan'], 0, ',', '.'); ?></strong></td>
                <td><span class="badge-os <?php echo $badge[$row['status']] ?? 'badge-gray'; ?>"><?php echo $row['status']; ?></span></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id_pengadaan']; ?>" class="btn-action btn-edit"><i class="fas fa-edit"></i></a>
                </td>
            </tr>
            <?php endwhile; ?>
            <?php if (mysqli_num_rows($r) === 0): ?>
                <tr><td colspan="7" style="text-align:center; color:#94A3B8; padding:24px;">Belum ada data pengadaan ATK.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

</div>

<?php include 'partials/footer.php'; ?>
