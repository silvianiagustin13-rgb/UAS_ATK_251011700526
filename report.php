<?php
session_start();
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php"); exit();
}

require_once('vendor/autoload.php');
include 'koneksi.php';

$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator('OfficeStock');
$pdf->SetAuthor('OfficeStock Admin');
$pdf->SetTitle('Laporan Data Pengadaan ATK - OfficeStock');
$pdf->SetSubject('Laporan Pengadaan ATK');

$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetMargins(12, 14, 12);
$pdf->AddPage();

$html  = '<table cellpadding="4" cellspacing="0" style="width:100%; margin-bottom:12px;">';
$html .= '<tr>';
$html .= '<td style="width:60px; vertical-align:middle; text-align:center; font-size:30px;">&#128203;</td>';
$html .= '<td style="vertical-align:middle;">';
$html .= '<p style="margin:0; font-size:18pt; font-weight:bold; color:#14305C; font-family:helvetica;">OfficeStock</p>';
$html .= '<p style="margin:0; font-size:9pt; color:#555;">Sistem Pengadaan ATK &mdash; Laporan Data Pengadaan</p>';
$html .= '</td>';
$html .= '<td style="vertical-align:middle; text-align:right; font-size:8pt; color:#777;">';
$html .= 'Dicetak: ' . date('d/m/Y H:i') . '<br>Oleh: ' . htmlspecialchars($_SESSION['username']);
$html .= '</td>';
$html .= '</tr></table>';
$html .= '<hr style="border:2px solid #0E8C7F; margin-bottom:10px;">';

$html .= '<h2 style="text-align:center; color:#14305C; font-family:helvetica; font-size:13pt; margin:0 0 12px 0;">LAPORAN DATA PENGADAAN ALAT TULIS KANTOR (ATK)</h2>';

$html .= '<table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse; font-size:8.5pt;">';
$html .= '<thead>';
$html .= '<tr style="background-color:#14305C; color:white; font-weight:bold; text-align:center;">';
$html .= '<th style="width:25px;">No</th>';
$html .= '<th style="width:50px;">Foto</th>';
$html .= '<th style="width:50px;">Kode</th>';
$html .= '<th style="width:110px;">Nama Barang</th>';
$html .= '<th style="width:75px;">Kategori</th>';
$html .= '<th style="width:50px;">Jumlah</th>';
$html .= '<th style="width:75px;">Harga Satuan</th>';
$html .= '<th style="width:85px;">Total</th>';
$html .= '<th style="width:90px;">Supplier</th>';
$html .= '<th style="width:60px;">Tanggal</th>';
$html .= '<th>Status</th>';
$html .= '</tr>';
$html .= '</thead><tbody>';

$query  = "SELECT * FROM tb_pengadaan_atk ORDER BY tanggal_pengadaan DESC";
$result = mysqli_query($koneksi, $query);
$no     = 1;
$total_nilai = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $bg = ($no % 2 == 0) ? '#EEF2F9' : '#FFFFFF';
    $subtotal = $row['jumlah'] * $row['harga_satuan'];
    $total_nilai += $subtotal;

    $foto_cell = '&mdash;';
    $foto_path = 'uploads/' . $row['foto_barang'];
    if ($row['foto_barang'] && file_exists($foto_path)) {
        $ext  = strtolower(pathinfo($foto_path, PATHINFO_EXTENSION));
        $mime = ($ext == 'png') ? 'png' : 'jpeg';
        $b64  = base64_encode(file_get_contents($foto_path));
        $foto_cell = '<img src="data:image/' . $mime . ';base64,' . $b64 . '" width="42" height="36" style="border-radius:4px;">';
    }

    $html .= '<tr style="background-color:' . $bg . ';">';
    $html .= '<td style="text-align:center;">' . $no++ . '</td>';
    $html .= '<td style="text-align:center;">' . $foto_cell . '</td>';
    $html .= '<td style="text-align:center; color:#14305C; font-weight:bold;">' . htmlspecialchars($row['id_pengadaan']) . '</td>';
    $html .= '<td><b>' . htmlspecialchars($row['nama_barang']) . '</b></td>';
    $html .= '<td style="text-align:center;">' . htmlspecialchars($row['kategori']) . '</td>';
    $html .= '<td style="text-align:center;">' . $row['jumlah'] . ' ' . htmlspecialchars($row['satuan']) . '</td>';
    $html .= '<td style="text-align:right;">Rp' . number_format($row['harga_satuan'], 0, ',', '.') . '</td>';
    $html .= '<td style="text-align:right;">Rp' . number_format($subtotal, 0, ',', '.') . '</td>';
    $html .= '<td>' . htmlspecialchars($row['supplier'] ?: '-') . '</td>';
    $html .= '<td style="text-align:center;">' . date('d/m/Y', strtotime($row['tanggal_pengadaan'])) . '</td>';
    $html .= '<td style="text-align:center;">' . htmlspecialchars($row['status']) . '</td>';
    $html .= '</tr>';
}

$html .= '<tr style="background:#0F2042; color:white; font-weight:bold;">';
$html .= '<td colspan="7" style="text-align:right; padding:8px;">TOTAL NILAI SELURUH PENGADAAN:</td>';
$html .= '<td style="text-align:right; color:#F2A93B;">Rp' . number_format($total_nilai, 0, ',', '.') . '</td>';
$html .= '<td colspan="3"></td>';
$html .= '</tr>';

$html .= '</tbody></table>';

$html .= '<p style="font-size:7.5pt; color:#999; margin-top:10px; text-align:right;">* Laporan ini dibuat otomatis oleh sistem OfficeStock - Sistem Pengadaan ATK</p>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('laporan_pengadaan_atk_officestock.pdf', 'I');
?>
