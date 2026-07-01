<?php include 'partials/header.php'; ?>

<div class="main-content">

<script>
$(document).ready(function(){
    toastr.options = {
        positionClass: 'toast-top-right',
        timeOut: 3500,
        progressBar: true,
        closeButton: true,
        showDuration: 300,
        hideDuration: 300
    };
    <?php if (isset($_GET['success'])): ?>
        toastr.success('<?php echo addslashes(strip_tags($_GET['success'])); ?>', 'Berhasil!');
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        toastr.error('<?php echo addslashes(strip_tags($_GET['error'])); ?>', 'Gagal!');
    <?php endif; ?>
});
</script>

<div class="page-header">
    <h1><i class="fas fa-boxes-stacked me-2" style="color:#0E8C7F;"></i>Data <span>Pengadaan ATK</span></h1>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="tambah.php" class="btn-os-primary"><i class="fas fa-plus"></i> Tambah Data</a>
        <a href="report.php" class="btn-os-amber" target="_blank"><i class="fas fa-file-pdf"></i> Cetak PDF</a>
    </div>
</div>

<div class="os-card">
    <div class="card-body">
        <div style="overflow-x:auto;">
        <table class="os-table w-100" id="tabel_pengadaan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Supplier</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            include 'koneksi.php';
            $query  = "SELECT * FROM tb_pengadaan_atk ORDER BY tanggal_pengadaan DESC";
            $result = mysqli_query($koneksi, $query);
            $no = 1;
            $badge_map = ['Diterima'=>'badge-green','Diajukan'=>'badge-amber','Disetujui'=>'badge-teal','Ditolak'=>'badge-red'];
            $cat_map   = ['Alat Tulis'=>'badge-navy','Kertas & Cetak'=>'badge-teal','Peralatan Kantor'=>'badge-amber','Tinta & Toner'=>'badge-navy','Map & Arsip'=>'badge-teal','Lainnya'=>'badge-gray'];
            while ($row = mysqli_fetch_assoc($result)):
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td>
                    <?php if ($row['foto_barang'] && file_exists('uploads/' . $row['foto_barang'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['foto_barang']); ?>"
                             alt="foto"
                             style="width:60px;height:52px;object-fit:cover;border-radius:8px;border:1.5px solid #DCE5F4;">
                    <?php else: ?>
                        <div style="width:60px;height:52px;background:linear-gradient(135deg,#DCE5F4,#DDF4F0);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:#94A3B8;">
                            <i class="fas fa-box"></i>
                        </div>
                    <?php endif; ?>
                </td>
                <td><strong style="color:#14305C;"><?php echo htmlspecialchars($row['id_pengadaan']); ?></strong></td>
                <td style="max-width:160px;"><strong><?php echo htmlspecialchars($row['nama_barang']); ?></strong></td>
                <td><span class="badge-os <?php echo $cat_map[$row['kategori']] ?? 'badge-navy'; ?>"><?php echo $row['kategori']; ?></span></td>
                <td><?php echo $row['jumlah']; ?> <?php echo htmlspecialchars($row['satuan']); ?></td>
                <td><strong>Rp<?php echo number_format($row['harga_satuan'], 0, ',', '.'); ?></strong></td>
                <td><?php echo htmlspecialchars($row['supplier'] ?: '-'); ?></td>
                <td><?php echo date('d/m/Y', strtotime($row['tanggal_pengadaan'])); ?></td>
                <td><span class="badge-os <?php echo $badge_map[$row['status']] ?? 'badge-gray'; ?>"><?php echo $row['status']; ?></span></td>
                <td>
                    <a href="edit.php?id=<?php echo $row['id_pengadaan']; ?>" class="btn-action btn-edit">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="hapus.php?id=<?php echo $row['id_pengadaan']; ?>"
                       class="btn-action btn-delete"
                       onclick="return confirmDelete(this, '<?php echo htmlspecialchars($row['nama_barang']); ?>')">
                        <i class="fas fa-trash"></i> Hapus
                    </a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

</div>

<script>
$(document).ready(function() {
    $('#tabel_pengadaan').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.3/i18n/id.json'
        },
        order: [[8, 'desc']],
        pageLength: 10,
    });
});

function confirmDelete(el, name) {
    if (confirm('Hapus data "' + name + '"?\n\nData tidak dapat dikembalikan!')) {
        return true;
    }
    return false;
}
</script>

<?php include 'partials/footer.php'; ?>
