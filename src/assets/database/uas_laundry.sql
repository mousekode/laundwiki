-- SQL Schema for UAS Laundry Management System
-- Database: uas_laundry

CREATE DATABASE IF NOT EXISTS `db_laundwiki`;
USE `db_laundwiki`;

-- 1. Table: pelanggan (Master Customer Data)
DROP TABLE IF EXISTS `order_laundry`;
DROP TABLE IF EXISTS `pelanggan`;
CREATE TABLE `pelanggan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `telepon` VARCHAR(20) NOT NULL,
  `alamat` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Table: layanan (Master Service Data)
DROP TABLE IF EXISTS `layanan`;
CREATE TABLE `layanan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_layanan` VARCHAR(100) NOT NULL,
  `harga_per_kg` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Table: paket (Master Package Data)
DROP TABLE IF EXISTS `paket`;
CREATE TABLE `paket` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_paket` VARCHAR(100) NOT NULL,
  `biaya_tambahan` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `durasi_hari` INT NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Table: order_laundry (Transaction Data)
CREATE TABLE `order_laundry` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_pelanggan` INT NOT NULL,
  `id_layanan` INT NOT NULL,
  `id_paket` INT NOT NULL,
  `berat` DECIMAL(5,2) NOT NULL,
  `total_harga` DECIMAL(10,2) NOT NULL,
  `tanggal_masuk` DATE NOT NULL,
  `tanggal_selesai` DATE NOT NULL,
  `status_transaksi` ENUM('Antre', 'Dicuci', 'Selesai') NOT NULL DEFAULT 'Antre',
  `status_pembayaran` ENUM('Belum Bayar', 'Lunas') NOT NULL DEFAULT 'Belum Bayar',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`id_layanan`) REFERENCES `layanan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Master Pelanggan
INSERT INTO `pelanggan` (`nama`, `telepon`, `alamat`) VALUES
('Budi Santoso', '081234567890', 'Jl. Merdeka No. 12, Surabaya'),
('Siti Aminah', '082345678901', 'Jl. Melati No. 45, Sidoarjo'),
('Agus Wijaya', '083456789012', 'Jl. Mawar No. 7, Gresik'),
('Dewi Lestari', '085712345678', 'Jl. Hayam Wuruk No. 88, Denpasar'),
('Eko Prasetyo', '081987654321', 'Jl. Teuku Umar No. 12, Denpasar'),
('I Made Wirawan', '087860123456', 'Jl. Raya Ubud No. 10, Gianyar'),
('Ni Wayan Sri', '081239876543', 'Jl. Gajah Mada No. 24, Tabanan'),
('Rian Hidayat', '089655443322', 'Jl. Sudirman No. 5, Jambi'),
('Gede Ketut Suardika', '082144556677', 'Jl. Sunset Road No. 100, Kuta'),
('Anisa Rahmawati', '085233221100', 'Jl. Diponegoro No. 17, Denpasar');

-- Seed Master Layanan
INSERT INTO `layanan` (`nama_layanan`, `harga_per_kg`) VALUES
('Cuci Basah', 3000.00),
('Cuci Kering', 5000.00),
('Setrika Saja', 4000.00),
('Cuci & Setrika', 7000.00);

-- Seed Master Paket
INSERT INTO `paket` (`nama_paket`, `biaya_tambahan`, `durasi_hari`) VALUES
('Reguler', 0.00, 3),
('Ekspres', 2500.00, 1),
('Kilat', 5000.00, 0);

-- Seed Transaksi Order_Laundry
-- Transaction 1: Budi, Cuci & Setrika, Reguler (3kg, 3 * 7000 + 0 = 21000)
-- Transaction 2: Siti, Cuci Kering, Ekspres (2kg, 2 * 5000 + 3000 = 13000)
-- Transaction 3: Agus, Setrika Saja, Kilat (5kg, 5 * 4000 + 5000 = 25000)
INSERT INTO `order_laundry` (`id_pelanggan`, `id_layanan`, `id_paket`, `berat`, `total_harga`, `tanggal_masuk`, `tanggal_selesai`, `status_transaksi`, `status_pembayaran`) VALUES
(1, 4, 1, 3.00, 21000.00, '2026-06-12', '2026-06-15', 'Dicuci', 'Lunas'),
(2, 2, 2, 2.00, 12500.00, '2026-06-14', '2026-06-15', 'Antre', 'Belum Bayar'),
(3, 3, 3, 5.00, 25000.00, '2026-06-14', '2026-06-14', 'Selesai', 'Lunas');
