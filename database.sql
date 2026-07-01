/*
UAS Pemrograman Web 2 - Universitas Pamulang
Tema: Data Pengadaan ATK (Alat Tulis Kantor)
Database: db_pengadaan_atk
*/

CREATE DATABASE IF NOT EXISTS `db_pengadaan_atk` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE `db_pengadaan_atk`;

/* Table structure for table `tb_pengadaan_atk` */

DROP TABLE IF EXISTS `tb_pengadaan_atk`;

CREATE TABLE `tb_pengadaan_atk` (
  `id_pengadaan` varchar(20) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `kategori` enum('Alat Tulis','Kertas & Cetak','Peralatan Kantor','Tinta & Toner','Map & Arsip','Lainnya') NOT NULL,
  `jumlah` int(11) NOT NULL COMMENT 'jumlah unit/satuan barang',
  `satuan` varchar(20) NOT NULL DEFAULT 'pcs',
  `harga_satuan` decimal(12,2) NOT NULL,
  `supplier` varchar(100) DEFAULT NULL,
  `tanggal_pengadaan` date NOT NULL,
  `foto_barang` varchar(255) DEFAULT NULL,
  `status` enum('Diajukan','Disetujui','Diterima','Ditolak') DEFAULT 'Diajukan',
  PRIMARY KEY (`id_pengadaan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* Sample data for the table `tb_pengadaan_atk` */

INSERT INTO `tb_pengadaan_atk`
(`id_pengadaan`,`nama_barang`,`kategori`,`jumlah`,`satuan`,`harga_satuan`,`supplier`,`tanggal_pengadaan`,`foto_barang`,`status`) VALUES
('ATK001','Pulpen Standard AE7','Alat Tulis',100,'pcs',3500.00,'CV Sumber Makmur','2026-06-10',NULL,'Diterima'),
('ATK002','Kertas HVS A4 80gr','Kertas & Cetak',20,'rim',55000.00,'Toko ATK Sejahtera','2026-06-15',NULL,'Disetujui'),
('ATK003','Toner Printer Laserjet','Tinta & Toner',5,'unit',650000.00,'PT Mitra Office','2026-06-20',NULL,'Diajukan');

/* Table structure for table `tb_users` (Login) */

DROP TABLE IF EXISTS `tb_users`;

CREATE TABLE `tb_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/* Default admin account: username "admin" / password "admin123" */

INSERT INTO `tb_users` (`id`,`username`,`password`,`created_at`) VALUES
(1,'admin','admin123', NOW());
