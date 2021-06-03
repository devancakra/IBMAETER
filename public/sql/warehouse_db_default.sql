-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jun 2021 pada 19.50
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 7.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `warehouse_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id_absen` int(7) NOT NULL,
  `uid_absen` int(7) NOT NULL,
  `email_absen` varchar(99) NOT NULL,
  `status_absen` varchar(99) NOT NULL,
  `alasan_izin` varchar(50) NOT NULL,
  `bukti_izin` varchar(256) NOT NULL,
  `tgl_absen` date NOT NULL,
  `waktu_absen` time NOT NULL,
  `respons` varchar(15) NOT NULL COMMENT 'tiga kategori :"Masuk", "Pending", "Diterima", "Ditolak"',
  `komen_izin` varchar(99) NOT NULL,
  `waktu_komen` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `alur_barang`
--

CREATE TABLE `alur_barang` (
  `no_log` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `request` enum('Masuk','Keluar') NOT NULL COMMENT 'Awas dengan trigger!',
  `status` enum('Pending','Diterima','Ditolak') NOT NULL,
  `ubah_stok` int(11) NOT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `uid_alur_admin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Trigger `alur_barang`
--
DELIMITER $$
CREATE TRIGGER `log_visibilitas` AFTER INSERT ON `alur_barang` FOR EACH ROW BEGIN
DECLARE done INT DEFAULT FALSE;
DECLARE ids INT;
DECLARE roles INT;
DECLARE cur1 CURSOR FOR SELECT `uid` FROM `user`;
DECLARE cur2 CURSOR FOR SELECT `role` FROM `user`;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

OPEN cur1;
OPEN cur2;

	ins_loop: LOOP
            FETCH cur1 INTO ids;
            FETCH cur2 INTO roles;
            IF done THEN
                LEAVE ins_loop;
            END IF;
            INSERT INTO `alur_barang_visibility` VALUES (NULL, new.`no_log`, ids, roles, "Belum Dilihat", CURDATE());
        END LOOP;

CLOSE cur1;
CLOSE cur2;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `stok_dinamis` AFTER UPDATE ON `alur_barang` FOR EACH ROW BEGIN
IF (new.`status`="Diterima") THEN
IF (new.`request`="Masuk") THEN
	UPDATE `item` SET `item`.`stok` = `item`.`stok` + new.`ubah_stok`
	WHERE `item`.`id_item` = new.`id_item`;
ELSE
UPDATE `item` SET `item`.`stok` = `item`.`stok` - new.`ubah_stok`
	WHERE `item`.`id_item` = new.`id_item`;
    END IF;
    END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `alur_barang_visibility`
--

CREATE TABLE `alur_barang_visibility` (
  `id_visibility` int(11) NOT NULL,
  `no_log` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `status` enum('Dilihat','Belum Dilihat') NOT NULL,
  `waktu` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `item`
--

CREATE TABLE `item` (
  `id_item` int(4) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `kode_barang` text NOT NULL COMMENT '[1] = no kategori \r\n[2]~[4] = no alfabet tempat penyimpanan\r\n[5]~[8] = no produksi',
  `nama_item` varchar(99) NOT NULL,
  `stok` int(4) NOT NULL,
  `jenis` varchar(25) NOT NULL,
  `penyimpanan` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL COMMENT 'IDR only',
  `berat` float NOT NULL COMMENT 'gr/item'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `item`
--

INSERT INTO `item` (`id_item`, `id_supplier`, `kode_barang`, `nama_item`, `stok`, `jenis`, `penyimpanan`, `harga`, `berat`) VALUES
(1, 2, '2-006-9707', 'Sosis Sonice', 300, 'Daging', 'F', 21000, 432),
(2, 15, '1-002-0896', 'Ultra Milk Jumbo', 102, 'Cair', 'B', 112000, 250),
(3, 10, '1-003-3822', 'Listerine', 350, 'Cair', 'C', 18000, 120),
(4, 3, '5-001-4660', 'Roti Sisir Mr. Bread', 300, 'Padat', 'A', 5000, 100),
(5, 11, '2-001-3302', 'Daging Sapi', 42, 'Daging', 'A', 130000, 200),
(6, 8, '5-004-9425', 'Sikat Gigi Formula', 500, 'Padat', 'D', 12000, 50),
(7, 11, '4-005-6596', 'Wipol Karbol Cemara', 900, 'Mudah Terbakar', 'E', 15000, 900),
(8, 7, '3-007-9610', 'Minyak Goreng Filma', 200, 'Minyak', 'G', 210000, 8000),
(9, 10, '1-006-2539', 'Pepsodent Pasta Gigi', 2000, 'Cair', 'F', 5000, 75),
(10, 2, '5-006-9524', 'Permen Kopiko', 500, 'Padat', 'F', 7000, 150),
(11, 1, '5-004-7415', 'Yaki Sushi Nori (Rumput Laut)', 400, 'Padat', 'D', 50000, 50),
(12, 6, '3-005-6887', 'Minyak Kayu Putih Cap Lang', 470, 'Minyak', 'E', 22000, 60),
(13, 2, '5-007-2744', 'Silverqueen Coklat Piramid', 700, 'Padat', 'G', 50000, 500),
(14, 2, '5-006-9973', 'Leo Sapi Panggang Keripik Kentang Snack', 700, 'Padat', 'F', 15000, 48),
(15, 1, '5-007-5288', 'Lemonilo Mie Instant', 2000, 'Padat', 'G', 106500, 70),
(16, 1, '5-003-3373', 'Indomie Goreng 1 Dus', 400, 'Padat', 'C', 100000, 3000),
(17, 1, '5-002-2852', 'Mie Sedaap Goreng 1 Dus', 1900, 'Padat', 'B', 70000, 4500),
(18, 2, '1-001-3770', 'Buavita Jus Buah Jambu', 500, 'Cair', 'A', 25000, 1300),
(19, 12, '1-005-9649', 'Sunkist Orange Jus', 50, 'Cair', 'E', 34000, 750),
(20, 13, '5-007-7018', 'Facial Tissue Tisu Nice', 800, 'Padat', 'G', 29500, 900),
(21, 8, '5-005-4785', 'Chocolatos Dark Family Pack', 300, 'Padat', 'E', 22500, 66),
(22, 7, '1-006-4190', 'Blue Band Cake and Cookie Margarine Sachet', 450, 'Cair', 'F', 49700, 200),
(23, 2, '5-004-0566', 'Softex Comfort Slim', 500, 'Padat', 'D', 14500, 350),
(24, 13, '3-006-3854', 'Kara Minyak Kelapa', 300, 'Minyak', 'F', 57000, 2000),
(25, 3, '3-005-4261', 'Minyak Wijen Oh Guan Hing', 560, 'Minyak', 'E', 27500, 100),
(26, 14, '3-004-2981', 'Minyak Goreng Bimoli', 600, 'Minyak', 'D', 28000, 2100),
(27, 13, '3-003-8393', 'Minyak Goreng Sunco', 800, 'Minyak', 'C', 30000, 2100),
(28, 11, '2-005-9749', 'Premium Saikoro Wagyu Cubes Beef Meltic Pack', 300, 'Daging', 'E', 72000, 500),
(29, 2, '2-001-9015', 'Ayam Kampung Segar Fresh', 500, 'Daging', 'A', 60000, 700),
(30, 10, '2-001-1903', 'Ikan Salmon Norwegian Premium Fillet Fresh', 250, 'Daging', 'A', 29900, 100),
(31, 11, '2-002-0896', 'Fresh Daging Giling Ikan Tenggiri Papan', 500, 'Daging', 'B', 105000, 100),
(32, 11, '2-006-9190', 'Ikan Kakap Merah Fillet (Red Snapper)', 200, 'Daging', 'F', 87500, 500),
(33, 11, '2-005-8238', 'Fresh Kepiting Bakau', 50, 'Daging', 'E', 165000, 100),
(34, 13, '2-002-1389', 'Cumi Tube Kupas Bersih Segar Beku Kualitas Premium Super', 700, 'Daging', 'B', 47000, 100),
(35, 13, '2-006-9864', 'Cumi Ring Frozen', 220, 'Daging', 'F', 37500, 500),
(36, 13, '2-006-7135', 'Frozen Canadian Lobster Canada Udang Lobster Capit Import', 50, 'Daging', 'F', 695000, 500),
(37, 3, '4-003-1936', 'Baterai Remote ABC', 400, 'Mudah Terbakar', 'C', 5500, 200),
(38, 3, '4-002-1490', 'Baterai Alkalin', 500, 'Mudah Terbakar', 'B', 10500, 50),
(39, 14, '4-002-0763', 'Parfum Hugo Boss The Scent Edt Man', 350, 'Mudah Terbakar', 'B', 900000, 500),
(40, 10, '4-001-5972', 'Original Parfum Jaguar Classic Gold', 200, 'Mudah Terbakar', 'A', 205000, 500),
(41, 11, '4-007-9299', 'Evangeline Sakura Black Sakura Eau De Parfum', 255, 'Mudah Terbakar', 'G', 25500, 350),
(42, 15, '5-004-9328', 'Boneeto Coklat', 475, 'Padat', 'D', 53000, 750),
(43, 6, '1-001-5164', 'Good Day Rasa Moccachino', 250, 'Cair', 'A', 5000, 250),
(44, 6, '1-005-5606', 'Good Day Coffee Cappuccino', 225, 'Cair', 'E', 5000, 250),
(45, 6, '5-004-4727', 'Beng Beng Drink', 100, 'Padat', 'D', 15500, 375),
(46, 1, '5-004-7984', 'Biskuit &amp; Wafer Beng Beng Box', 500, 'Padat', 'D', 27500, 800),
(47, 5, '5-005-9967', 'Sido Muncul Vitamin D3 400 + Vitamin E 100 IU Soft Capsule', 600, 'Padat', 'E', 171475, 700),
(48, 5, '1-005-4636', 'Minyak Telon Tiga', 140, 'Cair', 'E', 42000, 500),
(49, 5, '5-003-9960', 'Fatraper', 130, 'Padat', 'C', 62000, 120),
(50, 5, '5-003-1105', 'Ramuan Minuman Tradisional TeJamu Kesehatan Daya Tahan Tubuh', 100, 'Padat', 'C', 12000, 250),
(51, 2, '2-001-6427', 'Sosis Sonice Spesial', 300, 'Daging', 'A', 70000, 432),
(52, 15, '1-002-3977', 'Ultra Milk Jumbo Spesial', 102, 'Cair', 'B', 200000, 250),
(53, 10, '1-003-1951', 'Listerine Spesial', 350, 'Cair', 'C', 30000, 120),
(54, 3, '5-001-9032', 'Roti Sisir Mr. Bread Spesial', 300, 'Padat', 'A', 7000, 100),
(55, 11, '2-001-1424', 'Daging Sapi Spesial', 42, 'Daging', 'A', 240000, 200),
(56, 8, '5-004-2307', 'Sikat Gigi Formula Spesial', 500, 'Padat', 'D', 18000, 50),
(57, 11, '4-005-0269', 'Wipol Karbol Cemara Spesial', 900, 'Mudah Terbakar', 'E', 28000, 900),
(58, 7, '3-007-2672', 'Minyak Goreng Filma Spesial', 200, 'Minyak', 'G', 180000, 8000),
(59, 10, '1-006-3816', 'Pepsodent Pasta Gigi Spesial', 2000, 'Cair', 'F', 7000, 75),
(60, 2, '5-006-7494', 'Permen Kopiko Spesial', 500, 'Padat', 'F', 10000, 150),
(61, 1, '5-004-1803', 'Yaki Sushi Nori (Rumput Laut) Spesial', 400, 'Padat', 'D', 80000, 50),
(62, 6, '3-005-8055', 'Minyak Kayu Putih Cap Lang Spesial', 470, 'Minyak', 'E', 40000, 60),
(63, 2, '5-007-7532', 'Silverqueen Coklat Piramid Spesial', 700, 'Padat', 'G', 80000, 500),
(64, 2, '5-006-1339', 'Leo Sapi Panggang Keripik Kentang Snack Spesial', 700, 'Padat', 'F', 28000, 48),
(65, 1, '5-007-2379', 'Lemonilo Mie Instant Spesial', 2000, 'Padat', 'G', 130000, 70),
(66, 1, '5-003-5908', 'Indomie Goreng 1 Dus Spesial', 400, 'Padat', 'C', 135000, 3000),
(67, 1, '5-002-1263', 'Mie Sedaap Goreng 1 Dus Spesial', 1900, 'Padat', 'B', 130000, 4500),
(68, 2, '1-001-6649', 'Buavita Jus Buah Jambu Spesial', 500, 'Cair', 'A', 18000, 1300),
(69, 12, '1-005-7782', 'Sunkist Orange Jus Spesial', 50, 'Cair', 'E', 50000, 750),
(70, 13, '5-007-3593', 'Facial Tissue Tisu Nice Spesial', 800, 'Padat', 'G', 98000, 900),
(71, 8, '5-005-9240', 'Chocolatos Dark Family Pack Spesial', 300, 'Padat', 'E', 40000, 66),
(72, 7, '1-006-3526', 'Blue Band Cake and Cookie Margarine Sachet Spesial', 450, 'Cair', 'F', 90000, 200),
(73, 2, '5-004-4431', 'Softex Comfort Slim Spesial', 500, 'Padat', 'D', 20000, 350),
(74, 13, '3-006-0863', 'Kara Minyak Kelapa Spesial', 300, 'Minyak', 'F', 100000, 2000),
(75, 3, '3-005-5409', 'Minyak Wijen Oh Guan Hing Spesial', 560, 'Minyak', 'E', 50000, 100),
(76, 14, '3-004-3615', 'Minyak Goreng Bimoli Spesial', 600, 'Minyak', 'D', 48000, 2100),
(77, 13, '3-003-2117', 'Minyak Goreng Sunco Spesial', 800, 'Minyak', 'C', 50000, 2100),
(78, 11, '2-005-1730', 'Premium Saikoro Wagyu Cubes Beef Meltic Pack Spesial', 300, 'Daging', 'E', 138000, 500),
(79, 2, '2-001-6511', 'Ayam Kampung Segar Fresh Spesial', 500, 'Daging', 'A', 110000, 700),
(80, 10, '2-001-9381', 'Ikan Salmon Norwegian Premium Fillet Fresh Spesial', 250, 'Daging', 'A', 25000, 100),
(81, 11, '2-002-7885', 'Fresh Daging Giling Ikan Tenggiri Papan Spesial', 500, 'Daging', 'B', 180000, 100),
(82, 11, '2-006-5217', 'Ikan Kakap Merah Fillet (Red Snapper) Spesial', 200, 'Daging', 'F', 168000, 500),
(83, 11, '2-005-9336', 'Fresh Kepiting Bakau Spesial', 50, 'Daging', 'E', 300000, 100),
(84, 13, '2-002-9254', 'Cumi Tube Kupas Bersih Segar Beku Kualitas Premium Super Spesial', 700, 'Daging', 'B', 88000, 100),
(85, 13, '2-006-5224', 'Cumi Ring Frozen Spesial', 220, 'Daging', 'F', 70000, 500),
(86, 13, '2-006-2295', 'Frozen Canadian Lobster Canada Udang Lobster Capit Import Spesial', 50, 'Daging', 'F', 1350000, 500),
(87, 3, '4-003-3900', 'Baterai Remote ABC Spesial', 400, 'Mudah Terbakar', 'C', 7000, 200),
(88, 3, '4-002-0439', 'Baterai Alkalin Spesial', 500, 'Mudah Terbakar', 'B', 18000, 50),
(89, 14, '4-002-4998', 'Parfum Hugo Boss The Scent Edt Man Spesial', 350, 'Mudah Terbakar', 'B', 1200000, 500),
(90, 10, '4-001-1437', 'Original Parfum Jaguar Classic Gold Spesial', 200, 'Mudah Terbakar', 'A', 380000, 500),
(91, 11, '4-007-9977', 'Evangeline Sakura Black Sakura Eau De Parfum Spesial', 255, 'Mudah Terbakar', 'G', 42500, 350),
(92, 15, '5-004-4951', 'Boneeto Coklat Spesial', 475, 'Padat', 'D', 80000, 750),
(93, 6, '1-001-0542', 'Good Day Rasa Moccachino Spesial', 250, 'Cair', 'A', 7000, 250),
(94, 6, '1-005-6110', 'Good Day Coffee Cappuccino Spesial', 225, 'Cair', 'E', 7000, 250),
(95, 6, '5-004-3233', 'Beng Beng Drink Spesial', 100, 'Padat', 'D', 25000, 375),
(96, 1, '5-004-5102', 'Biskuit &amp; Wafer Beng Beng Box Spesial', 500, 'Padat', 'D', 48000, 800),
(97, 5, '5-005-9817', 'Sido Muncul Vitamin D3 400 + Vitamin E 100 IU Soft Capsule Spesial', 600, 'Padat', 'E', 300000, 700),
(98, 5, '1-005-2386', 'Minyak Telon Tiga Spesial', 140, 'Cair', 'E', 75000, 500),
(99, 5, '5-003-6935', 'Fatraper Spesial', 130, 'Padat', 'C', 100000, 120),
(100, 5, '5-003-7026', 'Ramuan Minuman Tradisional TeJamu Kesehatan Daya Tahan Tubuh Spesial', 100, 'Padat', 'C', 18000, 250);

-- --------------------------------------------------------

--
-- Struktur dari tabel `komplain`
--

CREATE TABLE `komplain` (
  `id_komplain` int(11) NOT NULL,
  `no_komplain` varchar(50) NOT NULL,
  `uid_komplain` int(7) NOT NULL,
  `judul_komplain` varchar(100) NOT NULL,
  `isi_komplain` varchar(256) NOT NULL,
  `foto_komplain` varchar(256) NOT NULL,
  `waktu_komplain` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Trigger `komplain`
--
DELIMITER $$
CREATE TRIGGER `komp_visibilitas` AFTER INSERT ON `komplain` FOR EACH ROW BEGIN
DECLARE done INT DEFAULT FALSE;
DECLARE ids INT;
DECLARE roles INT;
DECLARE cur1 CURSOR FOR SELECT `uid` FROM `user`;
DECLARE cur2 CURSOR FOR SELECT `role` FROM `user`;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

OPEN cur1;
OPEN cur2;

	ins_loop: LOOP
            FETCH cur1 INTO ids;
            FETCH cur2 INTO roles;
            IF done THEN
                LEAVE ins_loop;
            END IF;
            INSERT INTO `komplain_visibility` VALUES (NULL, new.`no_komplain`, ids, roles, "Belum Dilihat", CURDATE());
        END LOOP;

CLOSE cur1;
CLOSE cur2;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `komplain_arsip`
--

CREATE TABLE `komplain_arsip` (
  `id_arsipKomp` int(11) NOT NULL,
  `no_arsipKomp` varchar(20) NOT NULL,
  `uid_arsipKomp` int(7) NOT NULL,
  `judul_arsipKomp` varchar(100) NOT NULL,
  `isi_arsipKomp` varchar(256) NOT NULL,
  `foto_arsipKomp` varchar(256) NOT NULL,
  `waktu_arsipKomp` datetime NOT NULL,
  `uid_arsipKomp_admin` int(11) NOT NULL,
  `status_arsipKomp` varchar(10) NOT NULL,
  `comment_arsipKomp` varchar(256) DEFAULT NULL,
  `commented_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `komplain_visibility`
--

CREATE TABLE `komplain_visibility` (
  `id_visibility` int(11) NOT NULL,
  `no_komplain` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `status` enum('Dilihat','Belum Dilihat') NOT NULL,
  `waktu` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL,
  `waktu` datetime NOT NULL,
  `judul` varchar(55) NOT NULL,
  `isi` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Trigger `pengumuman`
--
DELIMITER $$
CREATE TRIGGER `del_pengumuman_visib` AFTER DELETE ON `pengumuman` FOR EACH ROW BEGIN
DELETE FROM `pengumuman_visibility` WHERE `id_pengumuman` = old.`id_pengumuman`;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `isi_visibilitas` AFTER INSERT ON `pengumuman` FOR EACH ROW BEGIN
DECLARE done INT DEFAULT FALSE;
DECLARE ids INT;
DECLARE roles INT;
DECLARE cur1 CURSOR FOR SELECT `uid` FROM `user`;
DECLARE cur2 CURSOR FOR SELECT `role` FROM `user`;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

OPEN cur1;
OPEN cur2;

	ins_loop: LOOP
            FETCH cur1 INTO ids;
            FETCH cur2 INTO roles;
            IF done THEN
                LEAVE ins_loop;
            END IF;
            INSERT INTO `pengumuman_visibility` VALUES (NULL, new.`id_pengumuman`, ids, roles, "Belum Dilihat", new.`waktu`);
        END LOOP;

CLOSE cur1;
CLOSE cur2;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman_visibility`
--

CREATE TABLE `pengumuman_visibility` (
  `id_visibility` int(11) NOT NULL,
  `id_pengumuman` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  `status` enum('Dilihat','Belum Dilihat') NOT NULL,
  `waktu` datetime NOT NULL COMMENT 'dilihat pada waktu?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(50) NOT NULL,
  `no_telp` char(20) NOT NULL,
  `alamat` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `no_telp`, `alamat`) VALUES
(1, 'PT Indofood CBP Sukses Makmur, Tbk', '081282320000', 'Sudirman Plaza, Indofood Tower Lantai 23 Jl. Jend. Sudirman Kav 76-78 Jakarta 12'),
(2, 'PT Tiga Pilar Sejahtera Food, Tbk', '082822422223', 'Jl. Grompol - Jambangan No.km. 5,5, Dusun 3, Sepat, Kec. Masaran, Kabupaten Srag'),
(3, 'PT Akasha Wira International, Tbk', '083712239502', 'No., Jl. Raya Malang - Gempol No.25, Sengon, Sengonagun, Purwosari, Pasuruan, Ja'),
(4, 'PT ABC President Indonesia', '083782289504', 'Office Tower A Lantai 31 Unit A-H - Jl Casablanca Raya Kav 88, Jakarta Selatan -'),
(5, 'PT Sido Muncul, Tbk', '083932239555', 'Jl Cipete Raya No 81 Jakarta 12410, Indonesia. Nomor Telepon: 021 765 3535 Fax 7'),
(6, 'PT Tri Banyan Tirta Tbk', '083731395221', 'Kampung Pasir Dalem, Desa Babakan Pari, Sukabumi, Jawa Barat'),
(7, 'PT Wilmar Cahaya Indonesia Tbk', '083729502512', 'Jalan Industri Selatan 3 Blok GG 1, Kawasan Industri Jababeka, Cikarang, Bekasi'),
(8, 'PT Delta Djakarta Tbk', '083712221340', 'Jalan Jenderal Sudirman Kavling 76 -78, Sudirman Plaza lantai 27, Jakarta Selata'),
(9, 'PT Multi Bintang Indonesia Tbk', '085756782950', 'Jalan Daan Mogot Kilometer 19, Tangerang, Banten'),
(10, 'PT Mayora Indah Tbk', '085756882953', 'Jalan Tomang Raya Nomor 21 – 23, Jakarta Barat, Jakarta'),
(11, 'PT Nippon Indosari Corpindo Tbk', '085756766667', 'Jalan Selayar Blok A9, Kawasan Industri, Bekasi'),
(12, 'PT Sekar Bumi Tbk', '085256764668', 'Jalan Jenderal Sudirman Kavling 59, Plaza Asia Fl. Jakarta Selatan, Jakarta'),
(13, 'PT Sekar Laut Tbk', '081235914512', 'Jalan Jenderal Sudirman Kavling 7 -8, Wisma Nugra Santana lantai 8, Jakarta Sela'),
(14, 'PT Siantar Top Tbk', '082723423414', 'Jalan Tambak Sawah Nomor 21 – 23, Waru Sidoarjo'),
(15, 'PT Ultrajaya Milk Industry Co. Tbk', '083790904444', 'Jalan Rawaterate 1 Nomor 5, Cakung, Jakarta Timur, Jakarta');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `uid` int(7) NOT NULL,
  `nama` varchar(99) NOT NULL,
  `email` varchar(99) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(1) NOT NULL,
  `divisi_user` int(11) DEFAULT NULL,
  `picture` varchar(256) NOT NULL,
  `gender` enum('Laki-laki','Perempuan') NOT NULL,
  `tanggal_lahir` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`uid`, `nama`, `email`, `password`, `role`, `divisi_user`, `picture`, `gender`, `tanggal_lahir`) VALUES
(1, 'Nikola Tesla', 'tesla@gmail.com', '$2y$10$NHGv1T3eNUlnNfPjlgCiB.npk/.9o8.B5lwZ2QiHcPsTA6r/Q9XH2', 0, 1, 'default.jpg', 'Laki-laki', '1977-05-25'),
(2, 'John Doe', 'johndoe@gmail.com', ' $2y$10$NHGv1T3eNUlnNfPjlgCiB.npk/.9o8.B5lwZ2QiHcPsTA6r/Q9XH2', 0, 10, 'default.jpg', 'Laki-laki', '1974-05-05'),
(3, 'Muhammad Al Fatih', 'fatih@gmail.com', ' $2y$10$NHGv1T3eNUlnNfPjlgCiB.npk/.9o8.B5lwZ2QiHcPsTA6r/Q9XH2', 0, 2, 'default.jpg', 'Laki-laki', '1979-07-26'),
(4, 'Khalid Walid', 'khalid@gmail.com', ' $2y$10$NHGv1T3eNUlnNfPjlgCiB.npk/.9o8.B5lwZ2QiHcPsTA6r/Q9XH2', 0, 2, 'default.jpg', 'Laki-laki', '1976-02-10'),
(5, 'Albert Einstein', 'einstein@gmail.com', '$2y$10$NHGv1T3eNUlnNfPjlgCiB.npk/.9o8.B5lwZ2QiHcPsTA6r/Q9XH2', 0, 10, 'default.jpg', 'Laki-laki', '1974-05-05'),
(6, 'Erwin Schrödinger', 'erwin@gmail.com', '$2y$10$NHGv1T3eNUlnNfPjlgCiB.npk/.9o8.B5lwZ2QiHcPsTA6r/Q9XH2', 0, 4, 'default.jpg', 'Laki-laki', '1977-05-25'),
(7, 'Allan Poe', 'allan@gmail.com', ' $2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 6, 'default.jpg', 'Laki-laki', '1987-01-30'),
(8, 'Paul A.M. Dirac', 'dirac@gmail.com', '$2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 5, 'default.jpg', 'Laki-laki', '1985-03-21'),
(9, 'Muhammad Ibnu', 'ibnu@gmail.com', ' $2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 5, 'default.jpg', 'Laki-laki', '1992-02-10'),
(10, 'Kurniawan Kuntoro', 'kukun@gmail.com', '$2y$10$ffptJql9WmysGHENlyUile/blayl4iLAvyvMRZd.9g2TaySULlfIG', 1, 8, 'default.jpg', 'Laki-laki', '1985-05-14'),
(11, 'Adielya Moline', 'adeline@gmail.com', '$2y$10$qkWqu1ODqi.744EeV3Re3OqBtLYnH8wxD4csHrJEylRU1jp.Ppgqi', 1, 7, 'default.jpg', 'Laki-laki', '1984-09-17'),
(12, 'Serline Claudya', 'serline@gmail.com', '$2y$10$ba3UBtfBOTOH5eDquRNZsOIBiYgOVq83WDbb2CXE3WhjJy.kAzTN.', 1, 6, 'default.jpg', 'Perempuan', '1989-05-14'),
(13, 'Alfha Fierly Firdaus', 'alfhaff@gmail.com', '$2y$10$wc851oFM4IwkxFJayGWyLuWuu4e2HImDwVaPD6MlWKUGnqp8u0eVG', 0, 3, 'default.jpg', 'Laki-laki', '1992-02-04'),
(14, 'Tasya Anastasya Mufida', 'tasyamufida@gmail.com', '$2y$10$lWgQ/EmPhz1G5vo.QXEAm.n8ac.21PZUgZV0zx4Tks2zASTH5TTya', 0, 1, 'default.jpg', 'Perempuan', '1994-02-02'),
(15, 'Billy Gate', 'billy@gmail.com', '$2y$10$oEZ2N2cpnzo8OsWbVYGXKuYjqFyh9wNoHEAD0OXc/cCO.kBr1KRIm', 0, 0, 'default.jpg', 'Laki-laki', '1984-05-08'),
(16, 'Jane Doe', 'janedoe@gmail.com', ' $2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 9, 'default.jpg', 'Perempuan', '0000-00-00'),
(17, 'Asuka Langley', 'asuka@gmail.com', ' $2y$10$NHGv1T3eNUlnNfPjlgCiB.npk/.9o8.B5lwZ2QiHcPsTA6r/Q9XH2', 0, 2, 'default.jpg', 'Perempuan', '1995-08-24'),
(18, 'Makinami Mari', 'makinami@gmail.com', '$2y$10$bs0KTnI2k1M8BMw8onU8C.frt28siCF5talRoKMTjotz4kOehkFSG', 0, 3, 'default.jpg', 'Perempuan', '1990-01-28'),
(19, 'Budi Anwar', 'budi@gmail.com', ' $2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 6, 'default.jpg', 'Laki-laki', '1994-06-05'),
(20, 'Nabila Sabila', 'nabila@gmail.com', ' $2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 7, 'default.jpg', 'Perempuan', '1991-03-26'),
(21, 'Sophia', 'sophia@gmail.com', ' $2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 8, 'default.jpg', 'Perempuan', '1989-02-20'),
(22, 'Aqila Waqidah', 'aqila@gmail.com', ' $2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 8, 'default.jpg', 'Perempuan', '1988-02-14'),
(23, 'Wahyu', 'wahyu@gmail.com', ' $2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 8, 'default.jpg', 'Laki-laki', '1991-02-16'),
(24, 'Fathur Rohman', 'fathur@gmail.com', ' $2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 9, 'default.jpg', 'Laki-laki', '1991-04-26'),
(25, 'Cantika', 'cantika@gmail.com', '$2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 9, 'default.jpg', 'Perempuan', '1990-05-15'),
(26, 'Ifrida Nabila', 'ifrida@gmail.com', ' $2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 9, 'default.jpg', 'Perempuan', '1993-02-20'),
(27, 'David Joestar', 'david@gmail.com', '$2y$10$xhULUdOUhwxBH1hCwrUoPeImopBifkkAeXwmD7NVg6uIV5HNiCYiy', 1, 9, 'default.jpg', 'Laki-laki', '1994-05-25'),
(28, 'Rizky Ahmad', 'rifkyakhmad911@gmail.com', '$2y$10$S4lLu3JNtZ7K2F8MB3iAiu84Pui4iEDuJAdYcEvf4eW/cTt.Pw5Pq', 0, 1, 'default.jpg', 'Laki-laki', '1998-05-26'),
(29, 'Eula Lawrence', 'rifkxc@gmail.com', '$2y$10$7UotMRfbctgZZK9kSh4FFeGCuSuSIFgHOT3IufP83PwPqmy/e67uu', 0, 2, 'default.jpg', 'Perempuan', '1994-10-25'),
(30, 'Jean', 'jean.brindilr@gmail.com', '$2y$10$LHZUsw3/Spyma54o8ydNiO0CGisJuYkGeEtzB7LmX8iDgvrSC5ASC', 0, 1, 'default.jpg', 'Perempuan', '2021-05-26'),
(31, 'Faisal', 'cv1@gmail.com', '$2y$10$bZTeYYc4vcfVUyrcrGpb4OgPFTB2LDNJ9jeE.zXHAX7K8rSr.dv2u', 0, 3, 'default.jpg', 'Laki-laki', '2021-05-26'),
(32, 'rifkya911', 'sdd911@gmail.com', '$2y$10$0XwDQb.gsioR4khSMMCvyONjoxYGtdCyX6ZnRzG8kh7IKgY4YoHJ.', 0, 8, 'default.jpg', 'Laki-laki', '2021-05-13'),
(33, 'Qiqi', 'qiqi@gmail.com', '$2y$10$LHZUsw3/Spyma54o8ydNiO0CGisJuYkGeEtzB7LmX8iDgvrSC5ASC', 0, 1, 'default.jpg', 'Perempuan', '1987-07-14');

--
-- Trigger `user`
--
DELIMITER $$
CREATE TRIGGER `update_role` AFTER UPDATE ON `user` FOR EACH ROW BEGIN

  update
    `komplain_visibility`
  set
    old.`role` = new.`role`
  WHERE `uid` = new.`uid`;
  
  UPDATE
    `pengumuman_visibility`
  SET
    old.`role` = new.`role`
  WHERE `uid` = new.`uid`;
  
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_activity`
--

CREATE TABLE `user_activity` (
  `id_aktivitas` int(7) NOT NULL,
  `uid_aktivitas` int(7) NOT NULL,
  `aktivitas` varchar(256) NOT NULL,
  `waktu_aktivitas` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_divisi`
--

CREATE TABLE `user_divisi` (
  `id_divisi` int(11) NOT NULL,
  `nama_divisi` varchar(35) NOT NULL,
  `kode_divisi` varchar(35) NOT NULL,
  `role_divisi` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `user_divisi`
--

INSERT INTO `user_divisi` (`id_divisi`, `nama_divisi`, `kode_divisi`, `role_divisi`) VALUES
(1, 'Dewan Direksi', 'DIR', 0),
(2, 'Manager Gudang', 'MNG', 0),
(3, 'Humas Gudang', 'HMS', 0),
(4, 'Pengadaan Barang', 'PGD', 0),
(5, 'Stacker Barang', 'STR', 1),
(6, 'Receiving Barang', 'RCV', 1),
(7, 'Checker Barang', 'CHK', 1),
(8, 'Dispatcher Barang', 'DSP', 1),
(9, 'Picker Barang', 'PCK', 1),
(10, 'IT Staff', 'ITS', 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absen`);

--
-- Indeks untuk tabel `alur_barang`
--
ALTER TABLE `alur_barang`
  ADD PRIMARY KEY (`no_log`);

--
-- Indeks untuk tabel `alur_barang_visibility`
--
ALTER TABLE `alur_barang_visibility`
  ADD PRIMARY KEY (`id_visibility`);

--
-- Indeks untuk tabel `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id_item`);

--
-- Indeks untuk tabel `komplain`
--
ALTER TABLE `komplain`
  ADD PRIMARY KEY (`id_komplain`);

--
-- Indeks untuk tabel `komplain_arsip`
--
ALTER TABLE `komplain_arsip`
  ADD PRIMARY KEY (`id_arsipKomp`);

--
-- Indeks untuk tabel `komplain_visibility`
--
ALTER TABLE `komplain_visibility`
  ADD PRIMARY KEY (`id_visibility`);

--
-- Indeks untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id_pengumuman`);

--
-- Indeks untuk tabel `pengumuman_visibility`
--
ALTER TABLE `pengumuman_visibility`
  ADD PRIMARY KEY (`id_visibility`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id_aktivitas`);

--
-- Indeks untuk tabel `user_divisi`
--
ALTER TABLE `user_divisi`
  ADD PRIMARY KEY (`id_divisi`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absen` int(7) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `alur_barang`
--
ALTER TABLE `alur_barang`
  MODIFY `no_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `alur_barang_visibility`
--
ALTER TABLE `alur_barang_visibility`
  MODIFY `id_visibility` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `item`
--
ALTER TABLE `item`
  MODIFY `id_item` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT untuk tabel `komplain`
--
ALTER TABLE `komplain`
  MODIFY `id_komplain` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `komplain_arsip`
--
ALTER TABLE `komplain_arsip`
  MODIFY `id_arsipKomp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `komplain_visibility`
--
ALTER TABLE `komplain_visibility`
  MODIFY `id_visibility` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengumuman_visibility`
--
ALTER TABLE `pengumuman_visibility`
  MODIFY `id_visibility` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id_aktivitas` int(7) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user_divisi`
--
ALTER TABLE `user_divisi`
  MODIFY `id_divisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
